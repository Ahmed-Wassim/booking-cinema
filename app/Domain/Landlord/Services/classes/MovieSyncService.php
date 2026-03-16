<?php

declare(strict_types=1);

namespace App\Domain\Landlord\Services\Classes;

use App\Domain\Landlord\MovieSync\Contracts\IGenreSyncService;
use App\Domain\Landlord\MovieSync\Contracts\IMovieImageService;
use App\Domain\Landlord\Repositories\Interfaces\IMovieRepository;
use App\Domain\Landlord\Repositories\Interfaces\ISupplierRepository;
use App\Domain\Landlord\Repositories\Interfaces\ISupplierSettingRepository;
use App\Domain\Landlord\Services\Interfaces\IMovieSyncService;
use App\Domain\Shared\Suppliers\Contracts\MovieSupplier;
use App\Domain\Shared\Suppliers\Factory\MovieSupplierFactory;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MovieSyncService implements IMovieSyncService
{
    public function __construct(
        protected ISupplierRepository $supplierRepository,
        protected ISupplierSettingRepository $supplierSettingRepository,
        protected IGenreSyncService $genreSyncService,
        protected IMovieRepository $movieRepository,
        protected IMovieImageService $movieImageService
    ) {
    }

    public function syncFromTmdb(): void
    {
        $this->syncBySupplierKey('tmdb');
    }

    public function sync(Supplier $supplier): void
    {
        $provider = $this->resolveProvider($supplier);
        if ($provider === null) {
            return;
        }

        $this->genreSyncService->sync($supplier, $provider);
        $this->syncMoviesWithProvider($supplier, $provider);
    }

    public function syncBySupplierKey(string $supplierKey): void
    {
        $supplier = $this->supplierRepository->findByKey($supplierKey);
        if (!$supplier instanceof Supplier) {
            Log::warning('Movie sync failed: supplier not found', [
                'supplier_key' => $supplierKey,
            ]);
            return;
        }
        $supplier->load('setting');
        $this->sync($supplier);
    }

    public function syncGenres(Supplier $supplier): void
    {
        $provider = $this->resolveProvider($supplier);
        if ($provider === null) {
            return;
        }
        $this->genreSyncService->sync($supplier, $provider);
    }

    public function syncMovies(Supplier $supplier): void
    {
        $provider = $this->resolveProvider($supplier);
        if ($provider === null) {
            return;
        }
        $this->syncMoviesWithProvider($supplier, $provider);
    }

    private function resolveProvider(Supplier $supplier): ?MovieSupplier
    {
        $setting = $this->supplierSettingRepository->getBySupplierId((int) $supplier->id);

        if (!$setting || !($setting->settings['api_key'] ?? $setting->getApiKey())) {
            Log::warning('Movie sync failed: supplier has no API key', [
                'supplier_key' => $supplier->key,
                'supplier_id'  => $supplier->id,
            ]);
            return null;
        }

        return MovieSupplierFactory::make($supplier->key, $setting->settings ?? []);
    }

    private function syncMoviesWithProvider(Supplier $supplier, MovieSupplier $provider): void
    {
        $pagesPerSync = config('moviesync.pages_per_sync', 2);
        $seenIds = [];

        for ($page = 1; $page <= $pagesPerSync; $page++) {
            $movies = $provider->fetchMovies($page);

            foreach ($movies as $movieDTO) {
                if ($movieDTO->id <= 0 || isset($seenIds[$movieDTO->id])) {
                    continue;
                }
                $seenIds[$movieDTO->id] = true;

                try {
                    DB::transaction(function () use ($supplier, $provider, $movieDTO) {
                        $movie = $this->movieRepository->updateOrCreateBySupplierAndExternal(
                            (int) $supplier->id,
                            $movieDTO->id,
                            [
                                'title'         => $movieDTO->title,
                                'overview'      => $movieDTO->overview,
                                'poster_path'   => $movieDTO->posterPath,
                                'backdrop_path' => $movieDTO->backdropPath,
                                'release_date'  => $movieDTO->releaseDate,
                                'runtime'       => null,
                                'language'      => $movieDTO->language,
                            ]
                        );

                        $this->movieRepository->syncGenres($movie, $movieDTO->genreIds);

                        $details = $provider->fetchMovieDetails((string) $movieDTO->id);
                        if ($details !== null) {
                            $movie->update([
                                'runtime'  => $details->runtime,
                                'language' => $details->language ?? $movie->language,
                            ]);
                        }

                        $this->movieImageService->storeMovieImages($movie, $movieDTO, $provider);
                    });
                } catch (\Throwable $e) {
                    Log::error('Movie sync failed for single movie', [
                        'supplier'     => $supplier->key,
                        'external_id'  => $movieDTO->id,
                        'movie_title'  => $movieDTO->title,
                        'error'        => $e->getMessage(),
                        'trace'        => $e->getTraceAsString(),
                    ]);
                }
            }
        }
    }
}

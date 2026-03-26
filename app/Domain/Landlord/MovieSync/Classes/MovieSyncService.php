<?php

declare(strict_types=1);

namespace App\Domain\Landlord\MovieSync\Classes;

use App\Domain\Landlord\Dashboard\Web\Movie\Repositories\Interfaces\IMovieRepository;
use App\Domain\Landlord\Dashboard\Web\Supplier\Repositories\Interfaces\ISupplierRepository;
use App\Domain\Landlord\Dashboard\Web\SupplierSetting\Repositories\Interfaces\ISupplierSettingRepository;
use App\Domain\Landlord\MovieSync\Interfaces\IGenreSyncService;
use App\Domain\Landlord\MovieSync\Interfaces\IMovieSyncService;
use App\Domain\Shared\Suppliers\Contracts\MovieSupplier;
use App\Domain\Shared\Suppliers\Factory\MovieSupplierFactory;
use App\Domain\Shared\Suppliers\Providers\TMDB\TMDBMovieSupplier;
use App\Models\Supplier;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MovieSyncService implements IMovieSyncService
{
    public function __construct(
        protected ISupplierRepository $supplierRepository,
        protected ISupplierSettingRepository $supplierSettingRepository,
        protected IGenreSyncService $genreSyncService,
        protected IMovieRepository $movieRepository,
    ) {}

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
        if (! $supplier instanceof Supplier) {
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

        if (! $setting || ! ($setting->settings['api_key'] ?? $setting->getApiKey())) {
            Log::warning('Movie sync failed: supplier has no API key', [
                'supplier_key' => $supplier->key,
                'supplier_id' => $supplier->id,
            ]);

            return null;
        }

        return MovieSupplierFactory::make($supplier->key, $setting->settings ?? []);
    }

    private function syncMoviesWithProvider(Supplier $supplier, MovieSupplier $provider): void
    {
        $pagesPerSync = (int) config('moviesync.pages_per_sync', 3);
        $cacheTtl = (int) config('moviesync.movie_cache_ttl', 3600);

        // Only TMDB supports parallel pool fetching; other providers fall back to sequential.
        if (! ($provider instanceof TMDBMovieSupplier)) {
            return;
        }

        $apiKey = $provider->getApiKey();

        // ── Step 1: Build all (endpoint × page) combos ───────────────────────
        /** @var array<int, array{endpoint: string, page: int}> $combos */
        $combos = [];
        foreach (TMDBMovieSupplier::ENDPOINTS as $endpoint) {
            for ($page = 1; $page <= $pagesPerSync; $page++) {
                $combos[] = ['endpoint' => $endpoint, 'page' => $page];
            }
        }

        // ── Step 2: Serve from cache OR fire all requests in parallel ─────────
        /** @var array<int, array<int, array<string, mixed>>> $pagedResults */
        $pagedResults = [];
        $missedCombos = [];
        $missedIndexes = [];

        foreach ($combos as $index => $combo) {
            $cacheKey = "tmdb:{$combo['endpoint']}:{$combo['page']}";
            $cached = Cache::get($cacheKey);
            if ($cached !== null) {
                $pagedResults[$index] = $cached;
            } else {
                $missedCombos[] = $combo;
                $missedIndexes[] = $index;
            }
        }

        if (! empty($missedCombos)) {
            $responses = Http::pool(function ($pool) use ($provider, $missedCombos, $apiKey) {
                $requests = [];
                foreach ($missedCombos as $combo) {
                    $requests[] = $pool->get(
                        $provider->endpointUrl($combo['endpoint']),
                        ['api_key' => $apiKey, 'page' => $combo['page']]
                    );
                }

                return $requests;
            });

            foreach ($responses as $i => $response) {
                $originalIndex = $missedIndexes[$i];
                $combo = $missedCombos[$i];
                $cacheKey = "tmdb:{$combo['endpoint']}:{$combo['page']}";

                if ($response instanceof \Throwable || ! $response->successful()) {
                    Log::warning('Movie sync: pool request failed', [
                        'supplier' => $supplier->key,
                        'endpoint' => $combo['endpoint'],
                        'page' => $combo['page'],
                        'status' => $response instanceof \Throwable ? 0 : $response->status(),
                    ]);
                    $pagedResults[$originalIndex] = [];

                    continue;
                }

                /** @var array<int, array<string, mixed>> $results */
                $results = $response->json('results', []);
                Cache::put($cacheKey, $results, $cacheTtl);
                $pagedResults[$originalIndex] = $results;
            }
        }

        // ── Step 3: Merge all pages into one flat array ───────────────────────
        /** @var array<int, array<string, mixed>> $allMovies */
        $allMovies = array_merge(...array_values($pagedResults));

        // ── Step 4: Deduplicate by TMDB id ────────────────────────────────────
        /** @var array<int, array<string, mixed>> $uniqueMovies */
        $uniqueMovies = collect($allMovies)
            ->keyBy('id')
            ->filter(fn ($item) => ! empty($item['id']))
            ->values()
            ->all();

        Log::info('Movie sync: deduplication complete', [
            'supplier' => $supplier->key,
            'raw_count' => count($allMovies),
            'unique_count' => count($uniqueMovies),
        ]);

        // ── Step 5: Store (updateOrCreate) — no details, no images ──────────
        foreach ($uniqueMovies as $item) {
            /** @var array<string, mixed> $item */
            try {
                $movie = $this->movieRepository->updateOrCreateBySupplierAndExternal(
                    (int) $supplier->id,
                    (int) $item['id'],
                    [
                        'title' => (string) ($item['title'] ?? ''),
                        'overview' => $item['overview'] ?? null,
                        'poster_path' => $item['poster_path'] ?? null,
                        'backdrop_path' => $item['backdrop_path'] ?? null,
                        'release_date' => $item['release_date'] ?? null,
                        'language' => $item['original_language'] ?? null,
                        'popularity' => isset($item['popularity']) ? (float) $item['popularity'] : null,
                    ]
                );

                /** @var array<int> $genreIds */
                $genreIds = $item['genre_ids'] ?? [];
                $this->movieRepository->syncGenres($movie, $genreIds);
            } catch (\Throwable $e) {
                Log::error('Movie sync failed for single movie', [
                    'supplier' => $supplier->key,
                    'external_id' => $item['id'] ?? null,
                    'title' => $item['title'] ?? null,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}

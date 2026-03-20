<?php

declare(strict_types=1);

namespace App\Domain\Landlord\MovieSync\Services;

use App\Domain\Landlord\MovieSync\Contracts\IGenreSyncService;
use App\Domain\Landlord\Dashboard\Web\Genre\Repositories\Interfaces\IGenreRepository;
use App\Domain\Shared\Suppliers\Contracts\MovieSupplier;
use App\Models\Supplier;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GenreSyncService implements IGenreSyncService
{
    public function __construct(
        protected IGenreRepository $genreRepository
    ) {
    }

    public function sync(Supplier $supplier, MovieSupplier $provider): void
    {
        $cacheKey = "supplier_genres_{$supplier->id}";
        $ttl = config('moviesync.genre_cache_ttl', 86400);

        $genres = Cache::remember($cacheKey, $ttl, fn () => $provider->fetchGenres());

        foreach ($genres as $genre) {
            if ($genre->id <= 0 || $genre->name === '') {
                Log::debug('Genre sync skipped invalid genre', [
                    'supplier' => $supplier->key,
                    'genre_id' => $genre->id,
                ]);
                continue;
            }
            $this->genreRepository->updateOrCreateByExternalId($genre->id, ['name' => $genre->name]);
        }
    }
}

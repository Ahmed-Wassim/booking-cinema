<?php

declare(strict_types=1);

namespace App\Domain\Shared\Suppliers\Providers\IMDb;

use App\Domain\Shared\Suppliers\Contracts\MovieSupplier;

/**
 * IMDb movie supplier implementation (stub). Add API integration when needed.
 */
class IMDbMovieSupplier implements MovieSupplier
{
    public function __construct(
        protected array $settings = []
    ) {
    }

    public function fetchMovies(string $endpoint, int $page = 1): array
    {
        return [];
    }

    public function fetchGenres(): array
    {
        return [];
    }
}

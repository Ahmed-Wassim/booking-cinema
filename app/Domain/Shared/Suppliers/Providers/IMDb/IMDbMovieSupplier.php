<?php

declare(strict_types=1);

namespace App\Domain\Shared\Suppliers\Providers\IMDb;

use App\Domain\Shared\Suppliers\Contracts\MovieSupplier;
use App\Domain\Shared\Suppliers\DTOs\MovieDetailsDTO;

/**
 * IMDb movie supplier implementation (stub). Add API integration when needed.
 */
class IMDbMovieSupplier implements MovieSupplier
{
    public function __construct(
        protected array $settings = []
    ) {
    }

    public function fetchMovies(int $page = 1): array
    {
        return [];
    }

    public function fetchGenres(): array
    {
        return [];
    }

    public function fetchMovieDetails(string $externalId): ?MovieDetailsDTO
    {
        return null;
    }

    public function posterUrl(?string $posterPath): ?string
    {
        return $posterPath ?: null;
    }

    public function backdropUrl(?string $backdropPath): ?string
    {
        return $backdropPath ?: null;
    }
}

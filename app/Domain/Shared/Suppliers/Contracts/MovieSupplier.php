<?php

declare(strict_types=1);

namespace App\Domain\Shared\Suppliers\Contracts;

use App\Domain\Shared\Suppliers\DTOs\GenreDTO;
use App\Domain\Shared\Suppliers\DTOs\MovieDetailsDTO;
use App\Domain\Shared\Suppliers\DTOs\MovieDTO;

interface MovieSupplier
{
    /**
     * @return array<int, MovieDTO>
     */
    public function fetchMovies(int $page = 1): array;

    /**
     * @return array<int, GenreDTO>
     */
    public function fetchGenres(): array;

    public function fetchMovieDetails(string $externalId): ?MovieDetailsDTO;

    /** Full URL for poster image (supplier-specific). */
    public function posterUrl(?string $posterPath): ?string;

    /** Full URL for backdrop image (supplier-specific). */
    public function backdropUrl(?string $backdropPath): ?string;
}

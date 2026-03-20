<?php

declare(strict_types=1);

namespace App\Domain\Shared\Suppliers\Contracts;

use App\Domain\Shared\Suppliers\DTOs\GenreDTO;
use App\Domain\Shared\Suppliers\DTOs\MovieDTO;

interface MovieSupplier
{
    /**
     * Fetch one page of movies from a specific endpoint.
     *
     * @return MovieDTO[]
     */
    public function fetchMovies(string $endpoint, int $page = 1): array;

    /**
     * Fetch all genres from the supplier.
     *
     * @return GenreDTO[]
     */
    public function fetchGenres(): array;
}

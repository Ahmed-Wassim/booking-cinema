<?php

namespace App\Domain\Tenant\Dashboard\Api\Movie\Services\Interfaces;

use App\Models\Tenant\Movie;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface IMovieService
{
    /**
     * Add a movie from the Landlord DB into the Tenant DB.
     *
     * @throws \Exception
     */
    public function addMovieToTenant(array $data): Movie;

    /**
     * Get all movies from the Tenant DB.
     */
    public function listAllMovies(): LengthAwarePaginator;

    /**
     * Get all movies from the Landlord DB.
     */
    public function getLandlordMovies(): LengthAwarePaginator;

    /**
     * Update a Tenant Movie (e.g. status).
     */
    public function updateMovie(int $id, array $data): Movie;

    /**
     * Delete a Tenant Movie.
     */
    public function deleteMovie(int $id): bool;
}

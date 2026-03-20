<?php

namespace App\Domain\Tenant\Dashboard\Api\Movie\Services\Interfaces;

use App\Models\Tenant\Movie;

interface IMovieService
{
    /**
     * Add a movie from the Landlord DB into the Tenant DB.
     *
     * @param array $data
     * @return Movie
     * @throws \Exception
     */
    public function addMovieToTenant(array $data): Movie;

    /**
     * Get all movies from the Tenant DB.
     */
    public function listAllMovies();

    /**
     * Get all movies from the Landlord DB.
     */
    public function getLandlordMovies();

    /**
     * Update a Tenant Movie (e.g. status).
     */
    public function updateMovie(int $id, array $data): Movie;

    /**
     * Delete a Tenant Movie.
     */
    public function deleteMovie(int $id): bool;
}

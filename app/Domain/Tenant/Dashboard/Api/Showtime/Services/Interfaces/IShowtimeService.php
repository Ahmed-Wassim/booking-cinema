<?php

namespace App\Domain\Tenant\Dashboard\Api\Showtime\Services\Interfaces;

use App\Models\Tenant\Showtime;
use Carbon\Carbon;

interface IShowtimeService
{
    /**
     * Create a new Showtime for a specific Tenant Movie.
     *
     * @param array $data
     * @return Showtime
     * @throws \Exception
     */
    public function createShowtime(array $data): Showtime;

    /**
     * Get all showtimes.
     */
    public function listAllShowtimes();

    /**
     * Update an existing Showtime.
     */
    public function updateShowtime(int $id, array $data): Showtime;

    /**
     * Delete a Showtime.
     */
    public function deleteShowtime(int $id): bool;
}

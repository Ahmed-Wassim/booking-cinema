<?php

namespace App\Domain\Tenant\Dashboard\Api\Showtime\Services\Interfaces;

use App\Models\Tenant\Showtime;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface IShowtimeService
{
    /**
     * Create a new Showtime for a specific Tenant Movie.
     *
     * @throws \Exception
     */
    public function createShowtime(array $data): Showtime;

    /**
     * Get all showtimes.
     */
    public function listAllShowtimes(): LengthAwarePaginator;

    /**
     * Update an existing Showtime.
     */
    public function updateShowtime(int $id, array $data): Showtime;

    /**
     * Delete a Showtime.
     */
    public function deleteShowtime(int $id): bool;

    /**
     * Reserve seats for a showtime for a specified duration (e.g., 10 minutes).
     *
     * @param int $showtimeId
     * @param array $seatIds
     * @return array
     */
    public function reserveSeats(int $showtimeId, array $seatIds): array;
}

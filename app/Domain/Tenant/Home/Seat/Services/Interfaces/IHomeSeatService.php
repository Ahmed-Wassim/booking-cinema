<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Home\Seat\Services\Interfaces;

use Illuminate\Support\Collection;

interface IHomeSeatService
{
    /**
     * Return all showtime seats (with physical seat details) for a given showtime.
     */
    public function getSeatsForShowtime(int $showtimeId): Collection;
}

<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Home\Showtime\Services\Interfaces;

use Illuminate\Support\Collection;

interface IHomeShowtimeService
{
    /**
     * Return showtimes for a given movie grouped by branch → date.
     */
    public function getShowtimesForMovie(int $movieId): Collection;
}

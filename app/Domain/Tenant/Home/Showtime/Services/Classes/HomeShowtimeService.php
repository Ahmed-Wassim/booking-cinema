<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Home\Showtime\Services\Classes;

use App\Domain\Tenant\Home\Showtime\Repositories\Interfaces\IHomeShowtimeRepository;
use App\Domain\Tenant\Home\Showtime\Services\Interfaces\IHomeShowtimeService;
use Illuminate\Support\Collection;

class HomeShowtimeService implements IHomeShowtimeService
{
    public function __construct(
        protected IHomeShowtimeRepository $showtimeRepository
    ) {}

    /**
     * Return all active showtimes for a movie, grouped by branch name then by date.
     *
     * Result shape:
     * [
     *   "Branch A" => [
     *     "2026-03-25" => [ <ShowtimeResource>, ... ],
     *   ],
     *   ...
     * ]
     */
    public function getShowtimesForMovie(int $movieId): Collection
    {
        $showtimes = collect($this->showtimeRepository->listAllBy(
            conditions: ['movie_id' => $movieId, 'status' => 'active'],
            relations:  ['hall.branch', 'priceTier'],
            orderBy:    'start_time',
            orderType:  'ASC',
        ));

        return $showtimes
            ->groupBy(fn ($s) => $s->hall->branch->name ?? 'Unknown Branch')
            ->map(fn ($branchShowtimes) => $branchShowtimes
                ->groupBy(fn ($s) => $s->start_time->toDateString())
            );
    }
}

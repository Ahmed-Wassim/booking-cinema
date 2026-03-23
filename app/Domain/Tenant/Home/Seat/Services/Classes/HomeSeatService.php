<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Home\Seat\Services\Classes;

use App\Domain\Tenant\Home\Seat\Repositories\Interfaces\IHomeSeatRepository;
use App\Domain\Tenant\Home\Seat\Services\Interfaces\IHomeSeatService;
use Illuminate\Support\Collection;

class HomeSeatService implements IHomeSeatService
{
    public function __construct(
        protected IHomeSeatRepository $seatRepository
    ) {}

    /**
     * Return all showtime seats with their physical seat details for a given showtime.
     */
    public function getSeatsForShowtime(int $showtimeId): Collection
    {
        return collect($this->seatRepository->listAllBy(
            conditions: ['showtime_id' => $showtimeId],
            relations:  ['seat'],
        ));
    }
}

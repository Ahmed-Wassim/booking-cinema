<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Home\Booking\Pipes;

use App\Domain\Tenant\Dashboard\Api\ShowtimeSeat\Repositories\Interfaces\IShowtimeSeatRepository;
use App\Enums\Tenant\ShowtimeSeatStatus;
use Closure;

class ReserveSeats
{
    public function __construct(protected IShowtimeSeatRepository $showtimeSeatRepository) {}

    public function handle(array $data, Closure $next): mixed
    {
        $seatIds    = $data['seat_ids'];
        $showtimeId = $data['showtime_id'];
        $seats      = $data['seats'];

        $this->showtimeSeatRepository->updateWhereIn(
            data: [
                'status'         => ShowtimeSeatStatus::BOOKED->value,
                'reserved_until' => null,
            ],
            ids: $seatIds,
            selectedColumn: 'id',
            conditions: ['showtime_id' => $showtimeId]
        );

        $data['total_price'] = (float) $seats->sum(
            fn ($showtimeSeat) => $showtimeSeat->seat?->priceTier?->price ?? 0
        );

        return $next($data);
    }
}

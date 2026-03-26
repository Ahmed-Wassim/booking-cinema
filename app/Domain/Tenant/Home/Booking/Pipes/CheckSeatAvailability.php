<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Home\Booking\Pipes;

use App\Domain\Tenant\Dashboard\Api\ShowtimeSeat\Repositories\Interfaces\IShowtimeSeatRepository;
use App\Enums\Tenant\ShowtimeSeatStatus;
use Closure;
use Exception;

class CheckSeatAvailability
{
    public function __construct(protected IShowtimeSeatRepository $showtimeSeatRepository) {}

    public function handle(array $data, Closure $next): mixed
    {
        $seatIds    = $data['seat_ids'];
        $showtimeId = $data['showtime_id'];

        $seats = $this->showtimeSeatRepository
            ->prepareQuery(conditions: ['showtime_id' => $showtimeId])
            ->whereIn('id', $seatIds)
            ->lockForUpdate()
            ->with(['seat.priceTier'])
            ->get();

        if ($seats->count() !== count($seatIds)) {
            throw new Exception('One or more selected seats do not exist for this showtime.');
        }

        foreach ($seats as $seat) {
            if ($seat->status === ShowtimeSeatStatus::BOOKED->value) {
                throw new Exception("Seat {$seat->seat_id} is already booked.");
            }

            if ($seat->status === ShowtimeSeatStatus::RESERVED->value) {
                if (! $seat->reserved_until || $seat->reserved_until->isPast()) {
                    throw new Exception("Seat {$seat->seat_id} reservation has expired. Please reserve it again.");
                }
                continue;
            }

            throw new Exception("Seat {$seat->seat_id} is not reserved. Please reserve it first.");
        }

        $data['seats'] = $seats;

        return $next($data);
    }
}

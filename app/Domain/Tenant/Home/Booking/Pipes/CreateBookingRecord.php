<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Home\Booking\Pipes;

use App\Domain\Tenant\Home\Booking\Enums\BookingStatus;
use App\Domain\Tenant\Home\Booking\Repositories\Interfaces\IBookingRepository;
use Closure;

class CreateBookingRecord
{
    public function __construct(protected IBookingRepository $bookingRepository) {}

    public function handle(array $data, Closure $next): mixed
    {
        $customer   = $data['customer_model'];
        $seats      = $data['seats'];
        $showtimeId = $data['showtime_id'];
        $totalPrice = $data['total_price'];
        $userId     = $data['user_id'] ?? null;

        $booking = $this->bookingRepository->create([
            'customer_id' => $customer->id,
            'user_id'     => $userId,
            'showtime_id' => $showtimeId,
            'total_price' => $totalPrice,
            'status'      => BookingStatus::PENDING->value,
            'expires_at'  => now()->addMinutes(10),
        ]);

        $bookingSeatsData = $seats->map(fn ($seat) => [
            'booking_id'       => $booking->id,
            'showtime_seat_id' => $seat->id,
            'price'            => $seat->seat?->priceTier?->price ?? 0,
            'created_at'       => now(),
            'updated_at'       => now(),
        ])->toArray();

        $booking->seats()->insert($bookingSeatsData);

        $data['booking'] = $booking;

        return $next($data);
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Home\Booking\Services\Classes;

use App\Domain\Tenant\Home\Booking\Enums\BookingStatus;
use App\Domain\Tenant\Home\Booking\Pipes\CheckSeatAvailability;
use App\Domain\Tenant\Home\Booking\Pipes\CreateBookingRecord;
use App\Domain\Tenant\Home\Booking\Pipes\ReserveSeats;
use App\Domain\Tenant\Home\Booking\Pipes\ResolveCustomer;
use App\Domain\Tenant\Home\Booking\Repositories\Interfaces\IBookingRepository;
use App\Domain\Tenant\Home\Booking\Services\Interfaces\IBookingService;
use App\Events\BookingConfirmed;
use App\Models\Tenant\Booking;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;

class BookingService implements IBookingService
{
    public function __construct(
        protected IBookingRepository $bookingRepository,
    ) {}

    public function createBooking(array $data): Booking
    {
        DB::beginTransaction();

        try {
            $booking = app(Pipeline::class)
                ->send($data)
                ->through([
                    CheckSeatAvailability::class,
                    ReserveSeats::class,
                    ResolveCustomer::class,
                    CreateBookingRecord::class,
                ])
                ->then(fn (array $data) => $data['booking']);

            DB::commit();

            return $booking;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function confirmBookingPayment(int $bookingId): Booking
    {
        DB::beginTransaction();
        $booking = $this->bookingRepository->firstOrFail([
            'id' => $bookingId,
        ]);

        if ($booking->status !== BookingStatus::PENDING) {
            throw new \Exception('Invalid booking state');
        }

        $booking->update([
            'status' => BookingStatus::PAID->value,
        ]);

        event(new BookingConfirmed($booking));

        DB::commit();

        return $booking;
    }

    public function findBooking(int $id): Booking
    {
        return $this->bookingRepository->firstOrFail(
            conditions: ['id' => $id],
            relations: [
                'showtime.movie',
                'showtime.hall.branch',
                'seats.showtimeSeat.seat.priceTier',
                'payment',
                'tickets',
            ]
        );
    }
}

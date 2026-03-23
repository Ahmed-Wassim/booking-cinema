<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Home\Booking\Services\Classes;

use App\Domain\Tenant\Dashboard\Api\ShowtimeSeat\Repositories\Interfaces\IShowtimeSeatRepository;
use App\Domain\Tenant\Home\Booking\DTO\CustomerDTO;
use App\Domain\Tenant\Home\Booking\Enums\BookingStatus;
use App\Domain\Tenant\Home\Booking\Repositories\Interfaces\IBookingRepository;
use App\Domain\Tenant\Home\Booking\Repositories\Interfaces\ICustomerRepository;
use App\Domain\Tenant\Home\Booking\Services\Interfaces\IBookingService;
use App\Models\Tenant\Booking;
use App\Models\Tenant\Customer;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BookingService implements IBookingService
{
    public function __construct(
        protected IBookingRepository $bookingRepository,
        protected IShowtimeSeatRepository $showtimeSeatRepository,
        protected ICustomerRepository $customerRepository
    ) {}

    public function createBooking(array $data): Booking
    {
        $showtimeId = $data['showtime_id'];
        $seatIds = $data['seat_ids'];
        $userId = $data['user_id'] ?? null;
        /** @var CustomerDTO $customerDTO */
        $customerDTO = $data['customer'];

        return DB::transaction(function () use ($showtimeId, $seatIds, $userId, $customerDTO) {
            $seats = $this->loadAndValidateSeats($seatIds, $showtimeId);
            $totalPrice = $this->calculateTotalPrice($seats);
            $customer = $this->resolveCustomer($customerDTO);

            /** @var Booking $booking */
            $booking = $this->bookingRepository->create([
                'customer_id' => $customer->id,
                'user_id' => $userId,
                'showtime_id' => $showtimeId,
                'total_price' => $totalPrice,
                'status' => BookingStatus::PENDING->value,
                'expires_at' => now()->addMinutes(10),
            ]);

            $this->attachSeatsToBooking($booking, $seats);

            return $booking;
        });
    }

    protected function loadAndValidateSeats(array $seatIds, int $showtimeId): Collection
    {
        $seats = $this->showtimeSeatRepository->getWhereIn(
            ids: $seatIds,
            conditions: ['showtime_id' => $showtimeId],
            relations: ['seat.priceTier']
        );

        if ($seats->count() !== count($seatIds)) {
            throw new Exception('Invalid seats provided.');
        }

        return $seats;
    }

    protected function calculateTotalPrice(Collection $seats): float
    {
        return (float) $seats->sum(function ($showtimeSeat) {
            return $showtimeSeat->seat?->priceTier?->price ?? 0;
        });
    }

    protected function resolveCustomer(array $dto): Customer
    {
        $customer = $this->customerRepository->firstOrCreate(
            ['email' => $dto['email']],
            [
                'name' => $dto['name'],
                'phone_country_code' => $dto['phone_country_code'],
                'phone' => $dto['phone'],
            ]
        );

        $customer->update(['last_booking_at' => now()]);

        return $customer;
    }

    protected function attachSeatsToBooking(Booking $booking, Collection $seats): void
    {
        $bookingSeatsData = $seats->map(function ($seat) use ($booking) {
            return [
                'booking_id' => $booking->id,
                'showtime_seat_id' => $seat->id,
                'price' => $seat->seat?->priceTier?->price ?? 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();

        $booking->seats()->insert($bookingSeatsData);
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
            ]
        );
    }
}

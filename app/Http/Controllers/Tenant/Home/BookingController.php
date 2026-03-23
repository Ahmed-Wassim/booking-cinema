<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Home;

use App\Domain\Tenant\Home\Booking\DTO\BookingDTO;
use App\Domain\Tenant\Home\Booking\Services\Interfaces\IBookingService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Home\CreateBookingRequest;
use App\Http\Resources\Tenant\Home\BookingResource;

class BookingController extends Controller
{
    public function __construct(
        protected IBookingService $bookingService
    ) {}

    /**
     * POST /api/bookings
     * Create a new pending booking with the reserved seats.
     */
    public function store(CreateBookingRequest $request)
    {
        $data = (array) BookingDTO::fromRequest($request->validated());

        try {
            $booking = $this->bookingService->createBooking($data);

            // Reload with relations for the resource
            $booking = $this->bookingService->findBooking($booking->id);

            return new BookingResource($booking);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}

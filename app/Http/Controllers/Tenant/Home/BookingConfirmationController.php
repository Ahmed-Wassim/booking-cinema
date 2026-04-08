<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Home;

use App\Domain\Tenant\Home\Booking\Services\Interfaces\IBookingService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Tenant\Home\BookingConfirmationResource;

class BookingConfirmationController extends Controller
{
    public function __construct(
        protected IBookingService $bookingService
    ) {}

    /**
     * GET /api/booking/{id}/success
     * Return confirmation details for the successful booking.
     */
    public function show(int $id)
    {
        try {
            $booking = $this->bookingService->findBooking($id);

            return new BookingConfirmationResource($booking);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Booking not found.'], 404);
        }
    }
}

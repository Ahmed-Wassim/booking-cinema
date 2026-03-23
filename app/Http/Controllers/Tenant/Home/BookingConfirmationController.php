<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Home;

use App\Domain\Tenant\Home\CoreBooking\Services\Interfaces\IBookingService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Tenant\Home\BookingConfirmationResource;
use App\Domain\Tenant\Home\CoreBooking\Enums\BookingStatus;
use Illuminate\Http\JsonResponse;

class BookingConfirmationController extends Controller
{
    public function __construct(
        protected IBookingService $bookingService
    ) {}

    /**
     * GET /booking/{id}/success
     * Return confirmation details for the successful booking.
     */
    public function show(int $id)
    {
        try {
            $booking = $this->bookingService->findBooking($id);

            // Security check: only show confirmation if booking is actually paid
            if ($booking->status !== BookingStatus::PAID) {
                abort(403, 'Booking is not paid.');
            }

            return new BookingConfirmationResource($booking);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Booking not found.'], 404);
        }
    }
}

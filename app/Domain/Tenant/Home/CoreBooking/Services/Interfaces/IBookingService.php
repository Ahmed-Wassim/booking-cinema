<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Home\CoreBooking\Services\Interfaces;

use App\Models\Tenant\Booking;

interface IBookingService
{
    /**
     * Create a pending booking with associated seats.
     */
    public function createBooking(array $data): Booking;

    /**
     * Retrieve a booking with its relationships (showtime, movie, seats, payment).
     */
    public function findBooking(int $id): Booking;
}

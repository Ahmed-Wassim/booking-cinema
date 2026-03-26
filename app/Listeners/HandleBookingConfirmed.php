<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Domain\Tenant\Home\Booking\Pipes\GenerateTickets;
use App\Domain\Tenant\Home\Booking\Pipes\SendTicketEmail;
use App\Events\BookingConfirmed;
use Illuminate\Pipeline\Pipeline;

class HandleBookingConfirmed
{
    public function handle(BookingConfirmed $event): void
    {
        $booking = $event->booking->loadMissing([
            'customer',
            'showtime.movie',
            'seats.showtimeSeat.seat.priceTier',
            'tickets',
        ]);

        app(Pipeline::class)
            ->send([
                'booking' => $booking,
                'customer_model' => $booking->customer,
            ])
            ->through([
                GenerateTickets::class,
                SendTicketEmail::class,
            ])
            ->thenReturn();
    }
}

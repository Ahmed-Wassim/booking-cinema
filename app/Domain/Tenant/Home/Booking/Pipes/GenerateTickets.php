<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Home\Booking\Pipes;

use App\Models\Tenant\Ticket;
use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class GenerateTickets
{
    public function handle(array $data, Closure $next): mixed
    {
        $booking = $data['booking'];
        $seats   = $data['seats'];
        $tickets = new Collection();

        foreach ($seats as $showtimeSeat) {
            $seat      = $showtimeSeat->seat;
            $seatLabel = $seat ? "{$seat->row}{$seat->number}" : 'N/A';

            $ticketNumber = 'T-' . strtoupper(Str::random(8));

            $ticket = Ticket::create([
                'booking_id'    => $booking->id,
                'seat_label'    => $seatLabel,
                'seat_id'       => $seat?->id,
                'ticket_number' => $ticketNumber,
                'qr_code'       => (string) Str::uuid(),
            ]);

            $tickets->push($ticket);
        }

        $data['tickets'] = $tickets;

        return $next($data);
    }
}

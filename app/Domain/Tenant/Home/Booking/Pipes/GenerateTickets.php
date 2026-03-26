<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Home\Booking\Pipes;

use App\Domain\Tenant\Home\Booking\Services\Classes\TicketService;
use App\Models\Tenant\Ticket;
use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class GenerateTickets
{
    public function __construct(protected TicketService $ticketService) {}

    public function handle(array $data, Closure $next): mixed
    {
        $booking = $data['booking'];
        $booking->loadMissing([
            'showtime.movie',
            'seats.showtimeSeat.seat.priceTier',
            'tickets',
        ]);

        $seats = $data['seats']
            ?? $booking->seats
                ->pluck('showtimeSeat')
                ->filter()
                ->values();

        $tickets = new Collection();
        $existingTickets = $booking->tickets()->get();

        foreach ($seats as $showtimeSeat) {
            $seat      = $showtimeSeat->seat;
            $seatLabel = $seat ? "{$seat->row}{$seat->number}" : 'N/A';
            $existingTicket = $existingTickets->first(function (Ticket $ticket) use ($seat, $seatLabel) {
                if ($seat?->id !== null && $ticket->seat_id === $seat->id) {
                    return true;
                }

                return $ticket->seat_label === $seatLabel;
            });

            if ($existingTicket) {
                $tickets->push($existingTicket);
                continue;
            }

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
        $booking->setRelation('tickets', $tickets);
        $data['voucher_pdf_path'] = $this->ticketService->generateVoucherPdf($booking);

        return $next($data);
    }
}

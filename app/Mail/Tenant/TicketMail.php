<?php

namespace App\Mail\Tenant;

use App\Models\Tenant\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Booking $booking) {}

    public function build()
    {
        return $this->markdown('emails.tickets')
                    ->subject('🎟️ Your Cinema Tickets - ' . $this->booking->showtime->movie->title)
                    ->with(['booking' => $this->booking]);
    }
}

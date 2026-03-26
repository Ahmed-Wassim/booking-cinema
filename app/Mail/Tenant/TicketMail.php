<?php

namespace App\Mail\Tenant;

use App\Domain\Tenant\Home\Booking\Services\Classes\TicketService;
use App\Models\Tenant\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Booking $booking,
        public ?string $voucherPdfPath = null
    ) {}

    public function build()
    {
        $mail = $this->markdown('emails.tickets')
            ->subject('Your Cinema Tickets - ' . $this->booking->showtime->movie->title)
            ->with(['booking' => $this->booking]);

        if ($this->voucherPdfPath !== null) {
            $absolutePath = app(TicketService::class)->voucherAbsolutePath($this->booking);

            if (is_file($absolutePath)) {
                $mail->attach(
                    $absolutePath,
                    [
                        'as' => 'booking-'.$this->booking->id.'-voucher.pdf',
                        'mime' => 'application/pdf',
                    ]
                );
            }
        }

        return $mail;
    }
}

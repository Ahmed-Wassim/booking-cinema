@component('mail::message')
# Your Cinema Tickets

**Booking ID:** #{{ $booking->id }}<br>
**Movie:** {{ $booking->showtime->movie->title }}<br>
**Date:** {{ $booking->showtime->start_time->format('d M Y - h:i A') }}

@foreach($booking->tickets as $ticket)
    <hr>
    **Ticket #{{ $ticket->ticket_number }}**<br>
    **Seat:** {{ $ticket->seat_label ?? 'N/A' }}

    <div style="text-align: center; margin: 20px 0;">
        @php
            $qrImage = app(\App\Domain\Tenant\Home\Booking\Services\Classes\TicketService::class)->generateQrCode($ticket->qr_code);
        @endphp
        <img src="data:image/svg+xml;base64,{{ base64_encode($qrImage) }}" alt="QR Code" width="300"
            style="border: 2px solid #000; padding: 10px; background: #fff;">
    </div>

    <p style="text-align: center; font-size: 12px; color: #666;">
        Show this QR code at the gate
    </p>
@endforeach

**Important:** Each ticket must be scanned separately.
Your booking voucher is attached as a PDF.

Thanks for booking with us!
@endcomponent
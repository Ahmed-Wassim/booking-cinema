<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Home\Booking\Pipes;

use App\Mail\Tenant\TicketMail;
use Closure;
use Illuminate\Support\Facades\Mail;

class SendTicketEmail
{
    public function handle(array $data, Closure $next): mixed
    {
        $booking  = $data['booking'];
        $customer = $data['customer_model'] ?? $booking->customer;

        $booking->setRelation('tickets', $data['tickets']);

        if ($customer?->email) {
            Mail::to($customer->email)->queue(
                (new TicketMail($booking, $data['voucher_pdf_path'] ?? null))->afterCommit()
            );
        }

        return $next($data);
    }
}

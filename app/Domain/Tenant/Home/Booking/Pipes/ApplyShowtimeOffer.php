<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Home\Booking\Pipes;

use App\Models\Tenant\Showtime;
use Closure;

class ApplyShowtimeOffer
{
    public function handle(array $data, Closure $next): mixed
    {
        $showtime       = Showtime::find($data['showtime_id']);
        $offer          = (float) ($showtime?->offer_percentage ?? 0);
        $subtotal       = (float) $data['subtotal'];
        $offerAmount    = round($subtotal * $offer / 100, 2);

        $data['total_price']     = round($subtotal - $offerAmount, 2);
        $data['discount_amount'] = $offerAmount;
        $data['discount_id']     = null;

        return $next($data);
    }
}

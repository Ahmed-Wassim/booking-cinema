<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Home\Booking\Pipes;

use App\Domain\Tenant\Shared\Services\ActivityLogger;
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

        if ($offerAmount > 0) {
            ActivityLogger::log('showtime_offer_applied', $showtime, [
                'offer_percentage' => $offer,
                'discount_value'   => $offerAmount,
                'original_subtotal' => $subtotal,
            ]);
        }

        return $next($data);
    }
}

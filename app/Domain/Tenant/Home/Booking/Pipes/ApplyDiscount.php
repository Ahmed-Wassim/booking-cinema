<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Home\Booking\Pipes;

use App\Domain\Tenant\Shared\Services\ActivityLogger;
use App\Models\Tenant\Discount;
use Closure;
use Exception;

class ApplyDiscount
{
    public function handle(array $data, Closure $next): mixed
    {
        $code = $data['coupon_code'] ?? null;

        if (! $code) {
            return $next($data);
        }

        // Lock the row and check max_uses atomically
        $discount = Discount::where('code', $code)
            ->lockForUpdate()
            ->first();

        if (! $discount) {
            ActivityLogger::log('coupon_failed', null, [
                'code' => $code,
                'reason' => 'not_found',
            ]);
            throw new Exception("Coupon '{$code}' is invalid or expired.");
        }

        if (! $discount->isValid()) {
            ActivityLogger::log('coupon_failed', $discount, [
                'code' => $code,
                'reason' => 'invalid_or_expired',
            ]);
            throw new Exception("Coupon '{$code}' is invalid or expired.");
        }

        // Atomic safe increment — only if under limit
        $updated = Discount::where('id', $discount->id)
            ->where(function ($q) use ($discount) {
                if ($discount->max_uses !== null) {
                    $q->whereRaw('used_count < max_uses');
                }
            })
            ->increment('used_count');

        if ($updated === 0 && $discount->max_uses !== null) {
            ActivityLogger::log('coupon_failed', $discount, [
                'code' => $code,
                'reason' => 'max_uses_reached',
            ]);
            throw new Exception("Coupon '{$code}' has reached its usage limit.");
        }

        // Calculate coupon discount ON TOP of current total_price
        $currentTotal  = (float) $data['total_price'];
        $discountValue = (float) $discount->value;
        $couponAmount  = $discount->type === 'percentage'
            ? round($currentTotal * $discountValue / 100, 2)
            : min($discountValue, $currentTotal);

        $data['discount_id']     = $discount->id;
        $data['discount_amount'] = round(((float) ($data['discount_amount'] ?? 0)) + $couponAmount, 2);
        $data['total_price']     = round($currentTotal - $couponAmount, 2);

        ActivityLogger::log('coupon_applied', $discount, [
            'code' => $code,
            'discount_amount' => $couponAmount,
            'subtotal_before_discount' => $currentTotal,
        ]);

        return $next($data);
    }
}

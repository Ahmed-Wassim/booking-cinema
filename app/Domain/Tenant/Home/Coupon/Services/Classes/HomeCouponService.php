<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Home\Coupon\Services\Classes;

use App\Domain\Tenant\Home\Coupon\Repositories\Interfaces\IHomeDiscountRepository;
use App\Domain\Tenant\Home\Coupon\Services\Interfaces\IHomeCouponService;
use App\Domain\Tenant\Home\Seat\Repositories\Interfaces\IHomeSeatRepository;
use App\Domain\Tenant\Home\Showtime\Repositories\Interfaces\IHomeShowtimeRepository;

class HomeCouponService implements IHomeCouponService
{
    public function __construct(
        protected IHomeSeatRepository $seatRepository,
        protected IHomeShowtimeRepository $showtimeRepository,
        protected IHomeDiscountRepository $discountRepository,
    ) {}

    public function validateCoupon(array $data): array
    {
        $discount = $this->discountRepository->first([
            'code' => $data['code'],
        ]);

        if (! $discount || ! $discount->isValid()) {
            return [
                'valid' => false,
                'message' => 'Coupon code is invalid or expired.',
            ];
        }

        $seats = $this->seatRepository
            ->prepareQuery(conditions: ['showtime_id' => $data['showtime_id']])
            ->whereIn('id', $data['seat_ids'])
            ->with(['seat.priceTier'])
            ->get();

        if ($seats->count() !== count($data['seat_ids'])) {
            return [
                'valid' => false,
                'message' => 'One or more selected seats do not exist.',
            ];
        }

        $subtotal = (float) $seats->sum(
            fn ($seat) => $seat->seat?->priceTier?->price ?? 0
        );

        $showtime = $this->showtimeRepository->find((int) $data['showtime_id']);
        $offer = (float) ($showtime?->offer_percentage ?? 0);
        $offerDiscount = round($subtotal * $offer / 100, 2);
        $totalAfterOffer = round($subtotal - $offerDiscount, 2);

        $discountValue = (float) $discount->value;
        $couponDiscount = $discount->type === 'percentage'
            ? round($totalAfterOffer * $discountValue / 100, 2)
            : min($discountValue, $totalAfterOffer);

        $finalTotal = round($totalAfterOffer - $couponDiscount, 2);
        $totalDiscountAmount = round($offerDiscount + $couponDiscount, 2);

        return [
            'valid' => true,
            'subtotal' => $subtotal,
            'offer_discount' => $offerDiscount,
            'coupon_discount' => $couponDiscount,
            'discount_amount' => $totalDiscountAmount,
            'total_price' => $finalTotal,
            'coupon' => [
                'code' => $discount->code,
                'type' => $discount->type,
                'value' => $discount->value,
            ],
        ];
    }
}

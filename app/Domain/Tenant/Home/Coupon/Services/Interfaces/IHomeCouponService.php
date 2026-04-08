<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Home\Coupon\Services\Interfaces;

interface IHomeCouponService
{
    public function validateCoupon(array $data): array;
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Home;

use App\Domain\Tenant\Home\Coupon\Services\Interfaces\IHomeCouponService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Home\ValidateCouponRequest;
use Illuminate\Http\JsonResponse;

class CouponController extends Controller
{
    public function __construct(
        protected IHomeCouponService $couponService
    ) {}

    public function validate(ValidateCouponRequest $request): JsonResponse
    {
        $result = $this->couponService->validateCoupon($request->validated());

        return response()->json(
            $result,
            $result['valid'] ? 200 : 422
        );
    }
}

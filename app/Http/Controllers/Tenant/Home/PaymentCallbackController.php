<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Home;

use App\Domain\Tenant\Checkout\Payment\Services\Interfaces\IOrderPaymentService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PaymentCallbackController extends Controller
{
    public function __construct(
        protected IOrderPaymentService $orderPaymentService
    ) {}

    /**
     * POST /api/checkout/callback
     * Webhook called by PayTabs (S2S) to verify payment success.
     */
    public function handle(Request $request): JsonResponse
    {
        $isSuccess = $this->orderPaymentService->handleCallback($request->all());

        if (! $isSuccess) {
            return response()->json(['message' => 'Payment validation failed.'], 400);
        }

        return response()->json(['message' => 'Payment processed successfully.'], 200);
    }
}

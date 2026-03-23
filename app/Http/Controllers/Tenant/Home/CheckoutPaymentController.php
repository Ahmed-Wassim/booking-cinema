<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Home;

use App\Domain\Tenant\Checkout\Payment\Services\Interfaces\IOrderPaymentService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Home\InitiatePaymentRequest;
use Illuminate\Http\JsonResponse;

class CheckoutPaymentController extends Controller
{
    public function __construct(
        protected IOrderPaymentService $orderPaymentService
    ) {}

    /**
     * POST /api/checkout/initiate
     * Triggers the PaymentManager to prepare a PayTabs session.
     * Returns the PayTabs redirect_url.
     */
    public function store(InitiatePaymentRequest $request): JsonResponse
    {
        try {
            $data = $this->orderPaymentService->initiatePayment($request->validated());

            return response()->json([
                'success'      => true,
                'redirect_url' => $data['redirect_url'],
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }
}

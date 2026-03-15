<?php

declare(strict_types=1);

namespace App\Domain\Landlord\Billing\Payment\Services;

use App\Domain\Landlord\Enums\PaymentStatusEnum;
use App\Domain\Landlord\Repositories\Interfaces\IPaymentRepository;
use App\Domain\Landlord\Repositories\Interfaces\ISubscriptionRepository;
use App\Domain\Landlord\Services\Interfaces\IPaymentService;
use App\Domain\Shared\Payments\Manager\PaymentManager;
use App\Models\Plan;
use App\Models\RegistrationRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * This class replaces the old `PaymentService` and delegates the
 * gateway-specific work to the shared payment infrastructure.
 *
 * It continues to implement the existing `IPaymentService` contract so that
 * controllers and other callers remain untouched.
 */
class SubscriptionPaymentService implements IPaymentService
{
    public function __construct(
        protected IPaymentRepository $paymentRepository,
        protected ISubscriptionRepository $subscriptionRepository,
        protected PaymentManager $paymentManager
    ) {}

    /**
     * Initiate a payment using the shared manager.
     *
     * {@inheritDoc}
     */
    public function initiatePayment(array $data): array
    {
        // allow tests or callers to inject a plan object directly (avoids database hit)
        $plan = $data['plan'] ?? Plan::findOrFail($data['planId']);
        $tenantName = $data['tenantName'] ?? 'Cinema Owner';
        $tenantEmail = $data['tenantEmail'] ?? 'user@example.com';
        $cartId = $data['cartId'] ?? (string) Str::uuid();

        if (isset($data['registrationId'])) {
            RegistrationRequest::where('id', $data['registrationId'])->update(['plan_id' => $plan->id]);
        }

        $payment = $this->paymentRepository->create([
            'registration_request_id' => $data['registrationId'],
            'plan_id' => $plan->id,
            'payment_token' => $cartId,
            'status' => PaymentStatusEnum::PENDING->value,
            'amount' => $data['amount'] ?? $plan->price,
            'currency' => $data['currency'] ?? config('paytabs.currency', 'AED'),
        ]);

        try {
            $defaultReturn = route('landlord.payment.success');
        } catch (\Throwable $e) {
            $defaultReturn = '';
        }
        try {
            $defaultCallback = route('landlord.payment.callback');
        } catch (\Throwable $e) {
            $defaultCallback = '';
        }

        $response = $this->paymentManager->initiate([
            'merchant' => 'paytabs', // hardcoded for now
            'amount' => $payment->amount,
            'currency' => $payment->currency,
            'cart_id' => $payment->payment_token,
            'description' => "Subscription: {$plan->name}",
            'tenant_name' => $tenantName,
            'tenant_email' => $tenantEmail,
            'return_url' => $data['returnUrl'] ?? $defaultReturn,
            'callback_url' => $data['callbackUrl'] ?? $defaultCallback,
        ]);

        return [
            'redirect_url' => $response->redirectUrl,
            'payment_id' => $payment->id,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function handleCallback(array $data): bool
    {
        // logging is helpful in production but might not be wired during unit tests
        try {
            Log::info('Payment callback', $data);
        } catch (\Throwable $e) {
            // ignore logging failures when facade not set up
        }

        $cartId = $data['cart_id'] ?? null;
        $tranRef = $data['tran_ref'] ?? null;

        if (! $cartId && ! $tranRef) {
            try {
                Log::warning('Callback missing identifiers');
            } catch (\Throwable $e) {
                // ignore
            }

            return false;
        }

        $payment = $cartId
            ? $this->paymentRepository->findByToken($cartId)
            : $this->paymentRepository->findByRef($tranRef);

        if (! $payment) {
            try {
                Log::warning('Callback: no matching payment found', compact('cartId', 'tranRef'));
            } catch (\Throwable $e) {
                // ignore
            }

            return false;
        }

        $response = $this->paymentManager->handleCallback($data);

        $isSuccess = $response->isSuccessful;
        $newStatus = $isSuccess ? PaymentStatusEnum::PAID->value : PaymentStatusEnum::FAILED->value;

        $this->paymentRepository->updateStatus($payment->id, $newStatus, [
            'transaction_ref' => $tranRef,
            'callback_data' => $data,
        ]);

        return $isSuccess;
    }
}

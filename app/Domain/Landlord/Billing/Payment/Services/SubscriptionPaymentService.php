<?php

declare(strict_types=1);

namespace App\Domain\Landlord\Billing\Payment\Services;

use App\Domain\Landlord\Enums\PaymentStatusEnum;
use App\Domain\Landlord\Dashboard\Web\Payment\Repositories\Interfaces\IPaymentRepository;
use App\Domain\Landlord\Dashboard\Web\Plan\Repositories\Interfaces\IPlanRepository;
use App\Domain\Landlord\Dashboard\Web\Subscription\Repositories\Interfaces\ISubscriptionRepository;
use App\Domain\Landlord\Dashboard\Web\RegistrationRequest\Repositories\Interfaces\IRegistrationRequestRepository;
use App\Domain\Landlord\Dashboard\Web\Payment\Services\Interfaces\IPaymentService;
use App\Domain\Shared\Payments\Manager\PaymentManager;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

/**
 * Subscription Payment Service
 *
 * Handles subscription payment initiation and callback processing.
 * This service delegates gateway-specific operations to the shared PaymentManager
 * while maintaining the existing IPaymentService contract for backward compatibility.
 */
class SubscriptionPaymentService implements IPaymentService
{
    public function __construct(
        protected IPaymentRepository $paymentRepository,
        protected IPlanRepository $planRepository,
        protected ISubscriptionRepository $subscriptionRepository,
        protected IRegistrationRequestRepository $registrationRequestRepository,
        protected PaymentManager $paymentManager
    ) {}

    /**
     * Initiate a subscription payment.
     *
     * Flow:
     * 1. Prepare and validate input data (plan, tenant details, cart ID).
     * 2. Update registration request with selected plan if applicable.
     * 3. Create a pending payment record in the database.
     * 4. Prepare return and callback URLs.
     * 5. Initiate payment with the gateway via PaymentManager.
     * 6. Return redirect URL and payment ID for client redirection.
     *
     * @param  array  $data  Payment initiation data
     * @return array Contains 'redirect_url' and 'payment_id'
     *
     * @throws \InvalidArgumentException If required data is missing
     */
    public function initiatePayment(array $data): array
    {
        $this->validateInitiateData($data);

        $plan = $this->resolvePlan($data);
        $tenantDetails = $this->prepareTenantDetails($data);
        $cartId = $data['cartId'] ?? (string) Str::uuid();

        $this->updateRegistrationRequest($data, $plan);

        $payment = $this->createPendingPayment($data, $plan, $cartId);

        $urls = $this->prepareUrls($data);

        $gatewayResponse = $this->initiateGatewayPayment($payment, $plan, $tenantDetails, $urls);

        return [
            'redirect_url' => $gatewayResponse->redirectUrl,
            'payment_id' => $payment->id,
        ];
    }

    /**
     * Handle payment callback from the gateway.
     *
     * Flow:
     * 1. Log callback data for debugging.
     * 2. Identify the payment using cart ID or transaction reference.
     * 3. Process callback with PaymentManager to verify authenticity.
     * 4. Update payment status based on success/failure.
     * 5. Return success status.
     *
     * @param  array  $data  Callback data from payment gateway
     * @return bool True if payment was successful, false otherwise
     */
    public function handleCallback(array $data): bool
    {
        $this->logCallback($data);

        $payment = $this->findPayment($data);
        if (! $payment) {
            return false;
        }

        $response = $this->paymentManager->handleCallback($data);
        $isSuccess = $response->isSuccessful;

        $this->updatePaymentStatus($payment, $isSuccess, $data);

        return $isSuccess;
    }

    /**
     * Validate required data for payment initiation.
     */
    private function validateInitiateData(array $data): void
    {
        if (! isset($data['plan']) && ! isset($data['planId'])) {
            throw new \InvalidArgumentException('Either plan object or planId must be provided.');
        }
    }

    /**
     * Resolve the plan object from data.
     */
    private function resolvePlan(array $data): Model
    {
        if (isset($data['plan']) && is_object($data['plan']) && isset($data['plan']->id)) {
            return $this->planRepository->findOrFail((int) $data['plan']->id);
        }

        return $this->planRepository->findOrFail((int) $data['planId']);
    }

    /**
     * Prepare tenant details with defaults.
     */
    private function prepareTenantDetails(array $data): array
    {
        return [
            'name' => $data['tenantName'] ?? 'Cinema Owner',
            'email' => $data['tenantEmail'] ?? 'user@example.com',
        ];
    }

    /**
     * Update registration request with plan if registration ID is provided.
     */
    private function updateRegistrationRequest(array $data, Model $plan): void
    {
        if (isset($data['registrationId'])) {
            $this->registrationRequestRepository->updateWhere(
                ['plan_id' => $plan->id],
                ['id' => $data['registrationId']]
            );
        }
    }

    /**
     * Create a pending payment record.
     */
    private function createPendingPayment(array $data, Model $plan, string $cartId): object
    {
        return $this->paymentRepository->create([
            'registration_request_id' => $data['registrationId'] ?? null,
            'plan_id' => $plan->id,
            'payment_token' => $cartId,
            'status' => PaymentStatusEnum::PENDING->value,
            'amount' => $data['amount'] ?? $plan->price,
            'currency' => $data['currency'] ?? config('paytabs.currency', 'AED'),
        ]);
    }

    /**
     * Prepare return and callback URLs with fallbacks.
     */
    private function prepareUrls(array $data): array
    {
        $defaultReturn = $this->getRouteSafely('landlord.payment.success');
        $defaultCallback = $this->getRouteSafely('landlord.payment.callback');

        return [
            'return_url' => $data['returnUrl'] ?? $defaultReturn,
            'callback_url' => $data['callbackUrl'] ?? $defaultCallback,
        ];
    }

    /**
     * Safely get a route URL, returning empty string if route doesn't exist.
     */
    private function getRouteSafely(string $routeName): string
    {
        try {
            return route($routeName);
        } catch (\Throwable $e) {
            return '';
        }
    }

    /**
     * Initiate payment with the gateway.
     */
    private function initiateGatewayPayment(object $payment, Model $plan, array $tenantDetails, array $urls): object
    {
        return $this->paymentManager->initiate([
            'merchant' => 'paytabs', // TODO: Make configurable
            'amount' => $payment->amount,
            'currency' => $payment->currency,
            'cart_id' => $payment->payment_token,
            'description' => "Subscription: {$plan->name}",
            'tenant_name' => $tenantDetails['name'],
            'tenant_email' => $tenantDetails['email'],
            'return_url' => $urls['return_url'],
            'callback_url' => $urls['callback_url'],
        ]);
    }

    /**
     * Log callback data safely.
     */
    private function logCallback(array $data): void
    {
        try {
            Log::info('Payment callback received', $data);
        } catch (\Throwable $e) {
            // Ignore logging failures in tests or when facade not set up
        }
    }

    /**
     * Find payment by cart ID or transaction reference.
     */
    private function findPayment(array $data): ?object
    {
        $cartId = $data['cart_id'] ?? null;
        $tranRef = $data['tran_ref'] ?? null;

        if (! $cartId && ! $tranRef) {
            $this->logWarning('Callback missing identifiers');

            return null;
        }

        $payment = $cartId
            ? $this->paymentRepository->findByToken($cartId)
            : $this->paymentRepository->findByRef($tranRef);

        if (! $payment) {
            $this->logWarning('No matching payment found', compact('cartId', 'tranRef'));

            return null;
        }

        return $payment;
    }

    /**
     * Update payment status after callback processing.
     */
    private function updatePaymentStatus(object $payment, bool $isSuccess, array $data): void
    {
        $newStatus = $isSuccess ? PaymentStatusEnum::PAID->value : PaymentStatusEnum::FAILED->value;
        $tranRef = $data['tran_ref'] ?? null;

        $this->paymentRepository->updateStatus($payment->id, $newStatus, [
            'transaction_ref' => $tranRef,
            'callback_data' => $data,
        ]);
    }

    /**
     * Log warning safely.
     */
    private function logWarning(string $message, array $context = []): void
    {
        try {
            Log::warning($message, $context);
        } catch (\Throwable $e) {
            // Ignore
        }
    }
}

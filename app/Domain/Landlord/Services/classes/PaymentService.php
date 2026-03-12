<?php

declare(strict_types=1);

namespace App\Domain\Landlord\Services\Classes;

use App\Domain\Landlord\Repositories\Interfaces\IPaymentRepository;
use App\Domain\Landlord\Repositories\Interfaces\ISubscriptionRepository;
use App\Domain\Landlord\Services\Interfaces\IPaymentService;
use App\Models\Plan;
use App\Models\RegistrationRequest;
use App\Models\Subscription;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Paytabscom\Laravel_paytabs\paypage as PaytabsPaypage;

class PaymentService implements IPaymentService
{
    public function __construct(
        protected IPaymentRepository $paymentRepository,
        protected ISubscriptionRepository $subscriptionRepository,
    ) {}

    /**
     * Initiates a PayTabs payment session.
     * Creates a pending Payment record and a pending Subscription,
     * then returns the PayTabs redirect URL.
     */
    public function initiatePayment(array $data): array
    {
        $tenantId = $data['tenantId'];
        $plan = Plan::findOrFail($data['planId']);
        $tenantName = $data['tenantName'] ?? 'Cinema Owner';
        $tenantEmail = $data['tenantEmail'] ?? 'user@example.com';
        $cartId = $data['cartId'] ?? (string) Str::uuid();

        // Update RegistrationRequest with the selected plan (using direct ID for accuracy)
        if (isset($data['registrationId'])) {
            RegistrationRequest::where('id', $data['registrationId'])->update(['plan_id' => $plan->id]);
        }

        // 1. Create pending Payment record
        $payment = $this->paymentRepository->create([
            'tenant_id' => $tenantId,
            'plan_id' => $plan->id,
            'payment_token' => $cartId,
            'status' => 'paid', // Defaulting back to 'paid' based on previous revert
            'amount' => $data['amount'] ?? $plan->price,
            'currency' => $data['currency'] ?? config('paytabs.currency', 'AED'),
        ]);

        // 2. Create pending Subscription linked to this payment
        $this->subscriptionRepository->create([
            'tenant_id' => $tenantId,
            'plan_id' => $plan->id,
            'payment_id' => $payment->id,
            'status' => Subscription::STATUS_PENDING,
        ]);

        // 3. Build PayTabs payment page request
        $returnUrl = $data['returnUrl'] ?? route('landlord.payment.success');
        $callbackUrl = $data['callbackUrl'] ?? route('landlord.payment.callback');

        $result = (new PaytabsPaypage)
            ->sendPaymentCode('all')
            ->sendTransaction('sale', 'ecom')
            ->sendCart(
                $cartId,
                $payment->amount,
                "Subscription: {$plan->name}"
            )
            ->sendCustomerDetails(
                $tenantName,
                $tenantEmail,
                '0000000000', // Phone – not collected during registration
                'N/A', // Address
                'N/A', // City
                'N/A', // State
                'AE', // Country (ISO 2)
                '00000', // Zip
                request()->ip()
            )
            ->shipping_same_billing()
            ->sendHideShipping(true)
            ->sendURLs($returnUrl, $callbackUrl)
            ->sendLanguage('en')
            ->create_pay_page();

        if ($result instanceof \Illuminate\Http\RedirectResponse) {
            $redirectUrl = $result->getTargetUrl();
        } elseif (is_string($result)) {
            $redirectUrl = $result;
        } else {
            Log::error('PayTabs create_pay_page returned unexpected type', ['result' => $result]);
            throw new \RuntimeException('Failed to create PayTabs payment page. Check logs.');
        }

        return [
            'redirect_url' => $redirectUrl,
            'payment_id' => $payment->id,
        ];
    }

    /**
     * Handles the PayTabs server-to-server callback.
     */
    public function handleCallback(array $data): bool
    {
        Log::info('PayTabs Callback Received', $data);

        $cartId = $data['cart_id'] ?? null;
        $tranRef = $data['tran_ref'] ?? null;

        if (! $cartId && ! $tranRef) {
            Log::warning('PayTabs callback missing cart_id and tran_ref');

            return false;
        }

        $payment = $cartId
            ? $this->paymentRepository->findByToken($cartId)
            : $this->paymentRepository->findByRef($tranRef);

        if (! $payment) {
            Log::warning('PayTabs callback: no matching payment found', compact('cartId', 'tranRef'));

            return false;
        }

        $responseStatus = $data['payment_result']['response_status'] ?? null;
        $isSuccess = ($responseStatus === 'A');

        $newStatus = $isSuccess ? 'paid' : 'failed';

        $this->paymentRepository->updateStatus($payment->id, $newStatus, [
            'transaction_ref' => $tranRef,
            'callback_data' => $data,
        ]);

        if ($isSuccess) {
            $subscription = $this->subscriptionRepository->findPendingByTenantAndPlan(
                $payment->tenant_id,
                $payment->plan_id
            );

            if ($subscription) {
                $this->subscriptionRepository->activate($subscription->id, $payment->id);
            }
        }

        return $isSuccess;
    }
}

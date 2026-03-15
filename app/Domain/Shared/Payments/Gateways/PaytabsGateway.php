<?php

declare(strict_types=1);

namespace App\Domain\Shared\Payments\Gateways;

use App\Domain\Shared\Payments\Contracts\PaymentGateway;
use App\Domain\Shared\Payments\DTOs\PaymentRequest;
use App\Domain\Shared\Payments\DTOs\PaymentResponse;
use Illuminate\Support\Facades\Log;
/** @noinspection PhpUndefinedClassInspection */
use Paytabscom\Laravel_paytabs\paypage as PaytabsPaypage;

class PaytabsGateway implements PaymentGateway
{
    public function initiate(PaymentRequest $request): PaymentResponse
    {
        // Build the PayTabs pay page request exactly as before
        $result = (new PaytabsPaypage)
            ->sendPaymentCode('all')
            ->sendTransaction('sale', 'ecom')
            ->sendCart(
                $request->cart_id,
                $request->amount,
                $request->description ?? ''
            )
            ->sendCustomerDetails(
                $request->tenant_name ?? 'Unknown',
                $request->tenant_email ?? 'unknown@example.com',
                '0000000000',
                'N/A',
                'N/A',
                'N/A',
                'AE',
                '00000',
                request()->ip()
            )
            ->shipping_same_billing()
            ->sendHideShipping(true)
            ->sendURLs($request->return_url ?? '', $request->callback_url ?? '')
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

        return PaymentResponse::success([ 'redirectUrl' => $redirectUrl, 'raw' => ['result' => $result] ]);
    }

    public function handleCallback(array $payload): PaymentResponse
    {
        // determine success by the same logic as old service
        $responseStatus = $payload['payment_result']['response_status'] ?? null;
        $isSuccess = ($responseStatus === 'A');

        return $isSuccess
            ? PaymentResponse::success(['raw' => $payload])
            : PaymentResponse::failure(['raw' => $payload]);
    }
}

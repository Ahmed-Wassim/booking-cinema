<?php

use App\Domain\Shared\Payments\Manager\PaymentManager;
use Illuminate\Support\Str;

it('can initiate a paytabs request and returns redirect url', function () {
    // subclass the real factory so the type hint is satisfied
    $factory = new class extends \App\Domain\Shared\Payments\Factory\PaymentGatewayFactory {
        public function make($merchant = null) {
            return new class implements \App\Domain\Shared\Payments\Contracts\PaymentGateway {
                public function initiate(\App\Domain\Shared\Payments\DTOs\PaymentRequest $request): \App\Domain\Shared\Payments\DTOs\PaymentResponse {
                    return \App\Domain\Shared\Payments\DTOs\PaymentResponse::success(['redirectUrl' => 'https://dummy']);
                }
                public function handleCallback(array $payload): \App\Domain\Shared\Payments\DTOs\PaymentResponse {
                    return \App\Domain\Shared\Payments\DTOs\PaymentResponse::success(['raw' => $payload]);
                }
            };
        }
    };

    $manager = new PaymentManager($factory);

    $resp = $manager->initiate([
        'merchant' => 'paytabs',
        'amount' => 10.50,
        'currency' => 'AED',
        'cart_id' => (string) Str::uuid(),
        'description' => 'Test',
        'tenant_name' => 'Alice',
        'tenant_email' => 'alice@example.com',
        'return_url' => 'https://example.test/success',
        'callback_url' => 'https://example.test/callback',
    ]);

    expect($resp)->toBeInstanceOf(\App\Domain\Shared\Payments\DTOs\PaymentResponse::class)
        ->and($resp->isSuccessful)->toBeTrue()
        ->and($resp->redirectUrl)->toBeString();
});

it('interprets callback payloads correctly', function () {
    $manager = new PaymentManager(new \App\Domain\Shared\Payments\Factory\PaymentGatewayFactory());

    $successPayload = [
        'payment_result' => ['response_status' => 'A'],
    ];
    $successResp = $manager->handleCallback($successPayload);
    expect($successResp->isSuccessful)->toBeTrue();

    $failPayload = [
        'payment_result' => ['response_status' => 'F'],
    ];
    $failResp = $manager->handleCallback($failPayload);
    expect($failResp->isSuccessful)->toBeFalse();
});

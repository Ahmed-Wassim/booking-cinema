<?php

declare(strict_types=1);

namespace App\Domain\Shared\Payments\Manager;

use App\Domain\Shared\Payments\DTOs\PaymentRequest;
use App\Domain\Shared\Payments\DTOs\PaymentResponse;
use App\Domain\Shared\Payments\Factory\PaymentGatewayFactory;

class PaymentManager
{
    public function __construct(protected PaymentGatewayFactory $factory) {}

    public function initiate(array $data): PaymentResponse
    {
        $request = PaymentRequest::fromRequest($data);
        $gateway = $this->factory->make($request->merchant);

        return $gateway->initiate($request);
    }

    public function handleCallback(array $payload): PaymentResponse
    {
        // for now, always use paytabs; could inspect payload to decide
        $gateway = $this->factory->make('paytabs');

        return $gateway->handleCallback($payload);
    }
}

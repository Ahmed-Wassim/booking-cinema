<?php

declare(strict_types=1);

namespace App\Domain\Shared\Payments\Contracts;

use App\Domain\Shared\Payments\DTOs\PaymentRequest;
use App\Domain\Shared\Payments\DTOs\PaymentResponse;

interface PaymentGateway
{
    /**
     * Initiate a payment and return a response containing any necessary
     * information (redirect URL, merchant transaction id, etc.).
     */
    public function initiate(PaymentRequest $request): PaymentResponse;

    /**
     * Handle a provider callback or webhook and return a response which
     * encapsulates success/failure information.
     */
    public function handleCallback(array $payload): PaymentResponse;
}

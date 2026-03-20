<?php

namespace App\Domain\Landlord\Dashboard\Web\Payment\Services\Interfaces;

use App\Domain\Landlord\Dashboard\Web\Payment\DTO\PaymentDTO;

interface IPaymentService
{
    /**
     * Create a PayTabs payment session and a pending subscription.
     * Returns ['redirect_url' => string, 'payment_id' => int]
     */
    public function initiatePayment(array $data): array;

    /**
     * Verify a PayTabs server-to-server callback, update the payment
     * status, and activate the associated subscription if successful.
     * Returns true on success, false on failure.
     */
    public function handleCallback(array $data): bool;
}

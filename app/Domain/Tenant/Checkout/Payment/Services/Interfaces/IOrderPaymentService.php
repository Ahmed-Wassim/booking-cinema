<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Checkout\Payment\Services\Interfaces;

interface IOrderPaymentService
{
    /**
     * Initiate a payment with PayTabs.
     * Returns an array typically containing 'redirect_url' and 'payment_token'.
     */
    public function initiatePayment(array $data): array;

    /**
     * Handle the callback/webhook from PayTabs.
     * Confirms the booking on success.
     * Returns true on successful validation and update.
     */
    public function handleCallback(array $data): bool;
}

<?php

declare(strict_types=1);

namespace App\Domain\Shared\Currency\Services\Interfaces;

interface ICurrentCurrencyService
{
    /**
     * Set the current currency for the request.
     */
    public function set(string $currency): void;

    /**
     * Get the current currency.
     */
    public function get(): string;

    /**
     * Check if the current currency matches a specific code.
     */
    public function is(string $currency): bool;

    /**
     * Get all active currencies from database.
     */
    public function getActive(): array;

    /**
     * Check if a currency code is allowed.
     */
    public function isAllowed(string $currency): bool;
}

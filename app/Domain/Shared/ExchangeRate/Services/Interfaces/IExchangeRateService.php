<?php

declare(strict_types=1);

namespace App\Domain\Shared\ExchangeRate\Services\Interfaces;

interface IExchangeRateService
{
    /**
     * Get all exchange rates for a base currency.
     *
     * @param  string  $baseCurrency  ISO 4217 currency code (e.g., 'USD', 'EUR')
     * @return array The full API response with conversion_rates
     */
    public function getRates(string $baseCurrency): array;

    /**
     * Get a specific exchange rate between two currencies.
     *
     * @param  string  $from  Source currency code
     * @param  string  $to  Target currency code
     * @return float|null The exchange rate or null if not found
     */
    public function getRate(string $from, string $to): ?float;

    /**
     * Convert an amount from one currency to another.
     *
     * @param  float  $amount  The amount to convert
     * @param  string  $from  Source currency code
     * @param  string  $to  Target currency code
     * @return float|null The converted amount or null if conversion failed
     */
    public function convert(float $amount, string $from, string $to): ?float;
}

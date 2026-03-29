<?php

declare(strict_types=1);
use App\Domain\Shared\ExchangeRate\Services\Interfaces\IExchangeRateService;

if (! function_exists('exchangeRate')) {
    function exchangeRate(string $from, string $to): ?float
    {
        return resolve(IExchangeRateService::class)
            ->getRate($from, $to);
    }
}

if (! function_exists('convertCurrency')) {
    function convertCurrency(float $amount, string $from, string $to): ?float
    {
        return resolve(IExchangeRateService::class)
            ->convert($amount, $from, $to);
    }
}

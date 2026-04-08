<?php

declare(strict_types=1);

namespace App\Domain\Shared\ExchangeRate\Services\Classes;

use App\Domain\Shared\ExchangeRate\Services\Interfaces\IExchangeRateService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExchangeRateService implements IExchangeRateService
{
    private const API_BASE_URL = 'https://v6.exchangerate-api.com/v6';

    private const CACHE_TTL_SECONDS = 43200; // 12 hours

    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.exchangerate.key', env('EXCHANGE_RATE_API_KEY', ''));
    }

    public function getRates(string $baseCurrency): array
    {
        $baseCurrency = strtoupper($baseCurrency);
        $cacheKey = $this->getCacheKey($baseCurrency);

        return Cache::remember($cacheKey, self::CACHE_TTL_SECONDS, function () use ($baseCurrency) {
            return $this->fetchRatesFromApi($baseCurrency);
        });
    }

    public function getRate(string $from, string $to): ?float
    {

        $rates = $this->getRates($from);

        $to = strtoupper($to);

        if (! isset($rates['conversion_rates'][$to])) {
            return null;
        }

        return (float) $rates['conversion_rates'][$to];
    }

    public function convert(float $amount, string $from, string $to): ?float
    {
        $rate = $this->getRate($from, $to);

        if ($rate === null) {
            return null;
        }

        return round($amount * $rate, 2);
    }

    private function getCacheKey(string $baseCurrency): string
    {
        return sprintf('exchange_rate:%s:%s', $baseCurrency, now()->format('Y-m-d'));
    }

    private function fetchRatesFromApi(string $baseCurrency): array
    {
        $url = sprintf('%s/%s/latest/%s', self::API_BASE_URL, $this->apiKey, $baseCurrency);

        try {
            $response = Http::timeout(10)->get($url);

            if (! $response->successful()) {
                Log::error('ExchangeRate API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'base_currency' => $baseCurrency,
                ]);

                throw new \RuntimeException(
                    sprintf('ExchangeRate API request failed with status %d', $response->status())
                );
            }

            $data = $response->json();

            if (($data['result'] ?? '') !== 'success') {
                Log::error('ExchangeRate API returned error', [
                    'error_type' => $data['error-type'] ?? 'unknown',
                    'base_currency' => $baseCurrency,
                ]);

                throw new \RuntimeException(
                    sprintf('ExchangeRate API error: %s', $data['error-type'] ?? 'unknown')
                );
            }

            return $data;
        } catch (\Exception $e) {
            Log::error('ExchangeRate API exception', [
                'message' => $e->getMessage(),
                'base_currency' => $baseCurrency,
            ]);

            throw $e;
        }
    }
}

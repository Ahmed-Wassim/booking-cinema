<?php

declare(strict_types=1);

namespace App\Domain\Shared\Currency\Services;

use App\Models\Currency;
use Illuminate\Support\Facades\Cache;
use Stancl\Tenancy\Facades\Tenancy;

class CurrentCurrencyService
{
    private ?string $currency = null;

    /**
     * Set the current currency for the request.
     */
    public function set(string $currency): void
    {
        $currency = strtoupper(trim($currency));

        // Validate against active currencies in database
        if (! $this->isAllowed($currency)) {
            $currency = $this->getDefaultCurrency();
        }

        $this->currency = $currency;
    }

    /**
     * Get the current currency (returns active currency or default).
     */
    public function get(): string
    {
        return $this->currency ?? $this->getDefaultCurrency();
    }

    /**
     * Check if the current currency matches a specific code.
     */
    public function is(string $currency): bool
    {
        return $this->get() === strtoupper($currency);
    }

    /**
     * Get all active currencies from database (cached).
     */
    public function getActive(): array
    {
        $cacheKey = $this->getCacheKey('active');

        return Cache::remember($cacheKey, 3600, function () {
            $query = $this->getCurrencyQuery();

            return $query->where('is_active', true)
                ->pluck('code')
                ->toArray();
        });
    }

    /**
     * Get all active currencies with their details (code, name, symbol).
     */
    public function getActiveCurrenciesWithDetails(): array
    {
        $cacheKey = $this->getCacheKey('active_with_details');

        return Cache::remember($cacheKey, 3600, function () {
            $query = $this->getCurrencyQuery();

            return $query->where('is_active', true)
                ->get(['code', 'name', 'symbol'])
                ->toArray();
        });
    }

    /**
     * Check if a currency code is allowed (active in database).
     */
    public function isAllowed(string $currency): bool
    {
        return in_array(strtoupper($currency), $this->getActive());
    }

    /**
     * Get default currency for current context (tenant or global).
     */
    public function getDefaultCurrency(): string
    {
        $isTenant = tenancy()->initialized;

        if ($isTenant) {
            $cacheKey = $this->getCacheKey('default');

            return Cache::remember($cacheKey, 3600, function () {
                return $this->getCurrencyQuery()
                    ->where('is_default', true)
                    ->value('code') ?? config('app.currency', 'USD');
            });
        }

        return config('app.currency', 'USD');
    }

    /**
     * Get the symbol for the current currency.
     */
    public function getSymbol(): string
    {
        $currencyCode = $this->get();
        $cacheKey = $this->getCacheKey("symbol:{$currencyCode}");

        return Cache::remember($cacheKey, 3600, function () use ($currencyCode) {
            $symbol = $this->getCurrencyQuery()
                ->where('code', $currencyCode)
                ->value('symbol');

            return $symbol ?? $currencyCode;
        });
    }

    /**
     * Get the full currency object for the current currency.
     */
    public function getCurrencyObject()
    {
        $currencyCode = $this->get();
        $cacheKey = $this->getCacheKey("object:{$currencyCode}");

        return Cache::remember($cacheKey, 3600, function () use ($currencyCode) {
            return $this->getCurrencyQuery()
                ->where('code', $currencyCode)
                ->first();
        });
    }

    /**
     * Get the appropriate query builder for currencies.
     * Tenant context uses tenant_currencies, otherwise uses global currencies.
     */
    private function getCurrencyQuery()
    {
        $isTenant = tenancy()->initialized;

        if ($isTenant) {
            return \App\Models\Tenant\Currency::query();
        }

        return Currency::query();
    }

    /**
     * Get cache key based on tenant context.
     */
    private function getCacheKey(string $type): string
    {
        $isTenant = tenancy()->initialized;
        $tenantId = $isTenant ? Tenancy::getTenant()->id : 'global';

        return "currency:{$tenantId}:{$type}";
    }

    /**
     * Clear the currency cache (useful for tests or admin updates).
     */
    public static function clearCache(): void
    {
        Cache::flush();
    }
}

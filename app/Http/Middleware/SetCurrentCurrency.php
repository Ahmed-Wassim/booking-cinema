<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Domain\Shared\Currency\Services\CurrentCurrencyService;
use Closure;
use Illuminate\Http\Request;

class SetCurrentCurrency
{
    public function __construct(
        protected CurrentCurrencyService $currencyService
    ) {}

    public function handle(Request $request, Closure $next)
    {
        $currency = $this->resolveCurrency($request);
        $this->currencyService->set($currency);

        return $next($request);
    }

    /**
     * Resolve currency from request header only.
     * Header: Currency (e.g., Currency: USD)
     */
    protected function resolveCurrency(Request $request): string
    {
        return $request->header('Currency') 
            ?? config('app.currency', 'USD');
    }
}

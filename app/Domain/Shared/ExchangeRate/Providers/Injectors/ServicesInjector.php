<?php

declare(strict_types=1);

namespace App\Domain\Shared\ExchangeRate\Providers\Injectors;

use App\Domain\Shared\ExchangeRate\Services\Classes\ExchangeRateService;
use App\Domain\Shared\ExchangeRate\Services\Interfaces\IExchangeRateService;
use App\Providers\AppServiceProvider;

class ServicesInjector extends AppServiceProvider
{
    public function boot(): void
    {
        $this->app->scoped(IExchangeRateService::class, ExchangeRateService::class);
    }
}

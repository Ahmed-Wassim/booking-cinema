<?php

namespace App\Domain\Shared\ExchangeRate\Providers;

use App\Domain\Shared\ExchangeRate\Providers\Injectors\ServicesInjector;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(ServicesInjector::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

<?php

namespace App\Domain\Tenant\Dashboard\Api\Providers;

use App\Domain\Tenant\Dashboard\Api\Providers\Injectors\TenantRepositoriesInjector;
use App\Domain\Tenant\Dashboard\Api\Providers\Injectors\TenantServicesInjector;
use Illuminate\Support\ServiceProvider;

class TenantAppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(TenantRepositoriesInjector::class);
        $this->app->register(TenantServicesInjector::class);
    }

    public function boot(): void
    {
        //
    }
}

<?php

namespace App\Domain\Tenant\Home\Providers;

use App\Domain\Tenant\Home\Providers\Injectors\HomeRepositoriesInjector;
use App\Domain\Tenant\Home\Providers\Injectors\HomeServicesInjector;
use Illuminate\Support\ServiceProvider;

class HomeAppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(HomeRepositoriesInjector::class);
        $this->app->register(HomeServicesInjector::class);
    }

    public function boot(): void
    {
        //
    }
}

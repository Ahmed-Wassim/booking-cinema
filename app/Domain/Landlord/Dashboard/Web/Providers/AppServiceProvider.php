<?php

namespace App\Domain\Landlord\Dashboard\Web\Providers;

use App\Domain\Landlord\Dashboard\Web\Providers\Injectors\RepositoriesInjector;
use App\Domain\Landlord\Dashboard\Web\Providers\Injectors\ServicesInjector;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(RepositoriesInjector::class);
        $this->app->register(ServicesInjector::class);
    }

    public function boot(): void
    {
        //
    }
}

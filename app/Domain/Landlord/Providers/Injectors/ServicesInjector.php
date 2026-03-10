<?php

declare(strict_types=1);

namespace App\Domain\Landlord\Providers\Injectors;

use App\Domain\Landlord\Services\Classes\TenantService;
use App\Domain\Landlord\Services\Classes\UserService;
use App\Domain\Landlord\Services\Interfaces\IUserService;
use App\Domain\Landlord\Services\Interfaces\ITenantService;
use App\Providers\AppServiceProvider;

class ServicesInjector extends AppServiceProvider
{
    public function boot(): void
    {
        $this->app->scoped(IUserService::class, UserService::class);
        $this->app->scoped(ITenantService::class, TenantService::class);
    }
}

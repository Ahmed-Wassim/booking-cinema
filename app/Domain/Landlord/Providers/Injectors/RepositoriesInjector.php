<?php

declare(strict_types=1);

namespace App\Domain\Landlord\Providers\Injectors;

use App\Domain\Landlord\Repositories\Classes\TenantRepository;
use App\Domain\Landlord\Repositories\Classes\UserRepository;
use App\Domain\Landlord\Repositories\Interfaces\IPlanRepository;
use App\Domain\Landlord\Repositories\Interfaces\IUserRepository;
use App\Domain\Landlord\Repositories\Interfaces\ITenantRepository;
use App\Models\Plan;
use App\Models\Tenant;
use App\Models\User;
use App\Providers\AppServiceProvider;

class RepositoriesInjector extends AppServiceProvider
{
    public function boot(): void
    {
        $this->app->scoped(IUserRepository::class, function () {
            return new UserRepository(new User);
        });

        $this->app->scoped(ITenantRepository::class, function ($app) {
            return new TenantRepository(new Tenant());
        });

        $this->app->scoped(IPlanRepository::class, function ($app) {
            return new \App\Domain\Landlord\Repositories\Classes\PlanRepository(new Plan());
        });
    }
}

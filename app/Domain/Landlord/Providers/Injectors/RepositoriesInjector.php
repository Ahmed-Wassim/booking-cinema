<?php

declare(strict_types=1);

namespace App\Domain\Landlord\Providers\Injectors;

use App\Domain\Landlord\Repositories\Classes\TenantRepository;
use App\Domain\Landlord\Repositories\Classes\UserRepository;
use App\Domain\Landlord\Repositories\Interfaces\IUserRepository;
use App\Domain\Landlord\Repositories\Interfaces\ITenantRepository;
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

        $this->app->scoped(ITenantRepository::class, function () {
            return new TenantRepository(new Tenant);
        });
    }
}

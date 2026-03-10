<?php

declare(strict_types=1);

namespace App\Domain\Landlord\Providers\Injectors;

use App\Domain\Landlord\Repositories\Classes\UserRepository;
use App\Domain\Landlord\Repositories\Interfaces\IUserRepository;
use App\Models\User;
use App\Providers\AppServiceProvider;

class RepositoriesInjector extends AppServiceProvider
{
    public function boot(): void
    {
        $this->app->scoped(IUserRepository::class, function () {
            return new UserRepository(new User);
        });
    }
}

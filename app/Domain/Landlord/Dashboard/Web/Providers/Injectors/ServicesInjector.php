<?php

declare(strict_types=1);

namespace App\Domain\Landlord\Dashboard\Web\Providers\Injectors;

use App\Domain\Landlord\Billing\Payment\Services\SubscriptionPaymentService;
use App\Domain\Landlord\Dashboard\Web\Payment\Services\Interfaces\IPaymentService;
use App\Domain\Landlord\Dashboard\Web\Plan\Services\Classes\PlanService;
use App\Domain\Landlord\Dashboard\Web\Plan\Services\Interfaces\IPlanService;
use App\Domain\Landlord\Dashboard\Web\RegistrationRequest\Services\Classes\RegistrationRequestService;
use App\Domain\Landlord\Dashboard\Web\RegistrationRequest\Services\Interfaces\IRegistrationRequestService;
use App\Domain\Landlord\Dashboard\Web\Tenant\Services\Classes\TenantService;
use App\Domain\Landlord\Dashboard\Web\Tenant\Services\Interfaces\ITenantService;
use App\Domain\Landlord\Dashboard\Web\User\Services\Classes\UserService;
use App\Domain\Landlord\Dashboard\Web\User\Services\Interfaces\IUserService;
use App\Domain\Landlord\MovieSync\Classes\GenreSyncService;
use App\Domain\Landlord\MovieSync\Classes\MovieSyncService;
use App\Domain\Landlord\MovieSync\Interfaces\IGenreSyncService;
use App\Domain\Landlord\MovieSync\Interfaces\IMovieSyncService;
use App\Providers\AppServiceProvider;

class ServicesInjector extends AppServiceProvider
{
    public function boot(): void
    {
        $this->app->scoped(IUserService::class, UserService::class);
        $this->app->scoped(ITenantService::class, TenantService::class);
        $this->app->scoped(IPlanService::class, PlanService::class);
        $this->app->scoped(IRegistrationRequestService::class, RegistrationRequestService::class);
        // bind the new subscription payment service in place of the old one
        $this->app->scoped(IPaymentService::class, SubscriptionPaymentService::class);
        $this->app->scoped(IGenreSyncService::class, GenreSyncService::class);
        $this->app->scoped(IMovieSyncService::class, MovieSyncService::class);
    }
}

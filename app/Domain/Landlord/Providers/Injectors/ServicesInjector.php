<?php

declare(strict_types=1);

namespace App\Domain\Landlord\Providers\Injectors;

use App\Domain\Landlord\Billing\Payment\Services\SubscriptionPaymentService;
use App\Domain\Landlord\Services\Classes\PlanService;
use App\Domain\Landlord\Services\Classes\RegistrationRequestService;
use App\Domain\Landlord\Services\Classes\TenantService;
use App\Domain\Landlord\Services\Classes\UserService;
use App\Domain\Landlord\Services\Interfaces\IPaymentService;
use App\Domain\Landlord\Services\Interfaces\IPlanService;
use App\Domain\Landlord\Services\Interfaces\IRegistrationRequestService;
use App\Domain\Landlord\Services\Interfaces\ITenantService;
use App\Domain\Landlord\Services\Interfaces\IUserService;
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

    }
}

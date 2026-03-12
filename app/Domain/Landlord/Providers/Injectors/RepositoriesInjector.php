<?php

declare(strict_types = 1)
;

namespace App\Domain\Landlord\Providers\Injectors;

use App\Domain\Landlord\Repositories\Classes\PaymentRepository;
use App\Domain\Landlord\Repositories\Classes\RegistrationRequestRepository;
use App\Domain\Landlord\Repositories\Classes\SubscriptionRepository;
use App\Domain\Landlord\Repositories\Classes\TenantRepository;
use App\Domain\Landlord\Repositories\Classes\UserRepository;
use App\Domain\Landlord\Repositories\Interfaces\IPaymentRepository;
use App\Domain\Landlord\Repositories\Interfaces\IPlanRepository;
use App\Domain\Landlord\Repositories\Interfaces\IRegistrationRequestRepository;
use App\Domain\Landlord\Repositories\Interfaces\ISubscriptionRepository;
use App\Domain\Landlord\Repositories\Interfaces\ITenantRepository;
use App\Domain\Landlord\Repositories\Interfaces\IUserRepository;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\RegistrationRequest;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use App\Providers\AppServiceProvider;

class RepositoriesInjector extends AppServiceProvider
{
    public function boot(): void
    {
        $this->app->scoped(IUserRepository::class , function () {
            return new UserRepository(new User);
        });

        $this->app->scoped(ITenantRepository::class , function ($app) {
            return new TenantRepository(new Tenant());
        });

        $this->app->scoped(IPlanRepository::class , function ($app) {
            return new \App\Domain\Landlord\Repositories\Classes\PlanRepository(new Plan());
        });

        $this->app->scoped(IRegistrationRequestRepository::class , function ($app) {
            return new RegistrationRequestRepository(new RegistrationRequest());
        });

        $this->app->scoped(IPaymentRepository::class, function () {
            return new PaymentRepository(new Payment());
        });

        $this->app->scoped(ISubscriptionRepository::class, function () {
            return new SubscriptionRepository(new Subscription());
        });
    }

}

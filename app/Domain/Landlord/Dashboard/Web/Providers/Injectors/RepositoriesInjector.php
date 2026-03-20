<?php

declare(strict_types = 1)
;

namespace App\Domain\Landlord\Dashboard\Web\Providers\Injectors;

use App\Domain\Landlord\Dashboard\Web\Genre\Repositories\Classes\GenreRepository;
use App\Domain\Landlord\Dashboard\Web\Movie\Repositories\Classes\MovieRepository;
use App\Domain\Landlord\Dashboard\Web\Payment\Repositories\Classes\PaymentRepository;
use App\Domain\Landlord\Dashboard\Web\RegistrationRequest\Repositories\Classes\RegistrationRequestRepository;
use App\Domain\Landlord\Dashboard\Web\Supplier\Repositories\Classes\SupplierRepository;
use App\Domain\Landlord\Dashboard\Web\SupplierSetting\Repositories\Classes\SupplierSettingRepository;
use App\Domain\Landlord\Dashboard\Web\Subscription\Repositories\Classes\SubscriptionRepository;
use App\Domain\Landlord\Dashboard\Web\Tenant\Repositories\Classes\TenantRepository;
use App\Domain\Landlord\Dashboard\Web\User\Repositories\Classes\UserRepository;
use App\Domain\Landlord\Dashboard\Web\Genre\Repositories\Interfaces\IGenreRepository;
use App\Domain\Landlord\Dashboard\Web\Movie\Repositories\Interfaces\IMovieRepository;
use App\Domain\Landlord\Dashboard\Web\Payment\Repositories\Interfaces\IPaymentRepository;
use App\Domain\Landlord\Dashboard\Web\Plan\Repositories\Interfaces\IPlanRepository;
use App\Domain\Landlord\Dashboard\Web\RegistrationRequest\Repositories\Interfaces\IRegistrationRequestRepository;
use App\Domain\Landlord\Dashboard\Web\Supplier\Repositories\Interfaces\ISupplierRepository;
use App\Domain\Landlord\Dashboard\Web\SupplierSetting\Repositories\Interfaces\ISupplierSettingRepository;
use App\Domain\Landlord\Dashboard\Web\Subscription\Repositories\Interfaces\ISubscriptionRepository;
use App\Domain\Landlord\Dashboard\Web\Tenant\Repositories\Interfaces\ITenantRepository;
use App\Domain\Landlord\Dashboard\Web\User\Repositories\Interfaces\IUserRepository;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Supplier;
use App\Models\SupplierSetting;
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
            return new \App\Domain\Landlord\Dashboard\Web\Plan\Repositories\Classes\PlanRepository(new Plan());
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

        $this->app->scoped(ISupplierRepository::class, function () {
            return new SupplierRepository(new Supplier());
        });

        $this->app->scoped(ISupplierSettingRepository::class, function () {
            return new SupplierSettingRepository();
        });

        $this->app->scoped(IGenreRepository::class, function () {
            return new GenreRepository(new Genre());
        });

        $this->app->scoped(IMovieRepository::class, function () {
            return new MovieRepository(new Movie());
        });
    }

}

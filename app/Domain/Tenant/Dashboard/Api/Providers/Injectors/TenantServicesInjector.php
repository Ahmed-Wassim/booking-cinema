<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\Providers\Injectors;

use App\Domain\Tenant\Dashboard\Api\Branch\Services\Classes\BranchService;
use App\Domain\Tenant\Dashboard\Api\Branch\Services\Interfaces\IBranchService;
use App\Domain\Tenant\Dashboard\Api\Hall\Services\Classes\HallService;
use App\Domain\Tenant\Dashboard\Api\Hall\Services\Interfaces\IHallService;
use App\Domain\Tenant\Dashboard\Api\Movie\Services\Classes\MovieService;
use App\Domain\Tenant\Dashboard\Api\Movie\Services\Interfaces\IMovieService;
use App\Domain\Tenant\Dashboard\Api\Payment\Services\Classes\PaymentService;
use App\Domain\Tenant\Dashboard\Api\Payment\Services\Interfaces\IPaymentService;
use App\Domain\Tenant\Dashboard\Api\PriceTier\Services\Classes\PriceTierService;
use App\Domain\Tenant\Dashboard\Api\PriceTier\Services\Interfaces\IPriceTierService;
use App\Domain\Tenant\Dashboard\Api\Seat\Services\Classes\SeatService;
use App\Domain\Tenant\Dashboard\Api\Seat\Services\Interfaces\ISeatService;
use App\Domain\Tenant\Dashboard\Api\Showtime\Services\Classes\ShowtimeService;
use App\Domain\Tenant\Dashboard\Api\Showtime\Services\Interfaces\IShowtimeService;
use App\Domain\Tenant\Dashboard\Api\User\Services\Classes\UserService;
use App\Domain\Tenant\Dashboard\Api\User\Services\Interfaces\IUserService;
use App\Domain\Tenant\Dashboard\Api\ActivityLog\Services\Classes\ActivityLogService;
use App\Domain\Tenant\Dashboard\Api\ActivityLog\Services\Interfaces\IActivityLogService;
use App\Providers\AppServiceProvider;

class TenantServicesInjector extends AppServiceProvider
{
    public function boot(): void
    {
        $this->app->scoped(IBranchService::class, BranchService::class);
        $this->app->scoped(\App\Domain\Tenant\Dashboard\Api\Discount\Services\Interfaces\IDiscountService::class, \App\Domain\Tenant\Dashboard\Api\Discount\Services\Classes\DiscountService::class);
        $this->app->scoped(IHallService::class, HallService::class);
        $this->app->scoped(IPriceTierService::class, PriceTierService::class);
        $this->app->scoped(ISeatService::class, SeatService::class);
        $this->app->scoped(IUserService::class, UserService::class);
        $this->app->scoped(IMovieService::class, MovieService::class);
        $this->app->scoped(IPaymentService::class, PaymentService::class);
        $this->app->scoped(IShowtimeService::class, ShowtimeService::class);
        $this->app->scoped(IActivityLogService::class, ActivityLogService::class);
    }
}

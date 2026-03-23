<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Home\Providers\Injectors;

use App\Domain\Tenant\Checkout\Payment\Services\Classes\OrderPaymentService;
use App\Domain\Tenant\Checkout\Payment\Services\Interfaces\IOrderPaymentService;
use App\Domain\Tenant\Home\Booking\Services\Classes\BookingService;
use App\Domain\Tenant\Home\Booking\Services\Interfaces\IBookingService;
use App\Domain\Tenant\Home\Movie\Services\Classes\HomeMovieService;
use App\Domain\Tenant\Home\Movie\Services\Interfaces\IHomeMovieService;
use App\Domain\Tenant\Home\Seat\Services\Classes\HomeSeatService;
use App\Domain\Tenant\Home\Seat\Services\Interfaces\IHomeSeatService;
use App\Domain\Tenant\Home\Showtime\Services\Classes\HomeShowtimeService;
use App\Domain\Tenant\Home\Showtime\Services\Interfaces\IHomeShowtimeService;
use App\Providers\AppServiceProvider;

class HomeServicesInjector extends AppServiceProvider
{
    public function boot(): void
    {
        $this->app->scoped(IHomeMovieService::class, HomeMovieService::class);
        $this->app->scoped(IHomeShowtimeService::class, HomeShowtimeService::class);
        $this->app->scoped(IHomeSeatService::class, HomeSeatService::class);
        $this->app->scoped(IBookingService::class, BookingService::class);
        $this->app->scoped(IOrderPaymentService::class, OrderPaymentService::class);
    }
}

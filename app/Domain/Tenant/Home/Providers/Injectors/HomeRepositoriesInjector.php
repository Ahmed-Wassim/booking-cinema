<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Home\Providers\Injectors;

use App\Domain\Tenant\Checkout\Payment\Repositories\Classes\PaymentRepository;
use App\Domain\Tenant\Checkout\Payment\Repositories\Interfaces\IPaymentRepository;
use App\Domain\Tenant\Home\Booking\Repositories\Classes\BookingRepository;
use App\Domain\Tenant\Home\Booking\Repositories\Classes\CustomerRepository;
use App\Domain\Tenant\Home\Booking\Repositories\Interfaces\IBookingRepository;
use App\Domain\Tenant\Home\Booking\Repositories\Interfaces\ICustomerRepository;
use App\Domain\Tenant\Home\Coupon\Repositories\Classes\HomeDiscountRepository;
use App\Domain\Tenant\Home\Coupon\Repositories\Interfaces\IHomeDiscountRepository;
use App\Domain\Tenant\Home\Seat\Repositories\Classes\HomeSeatRepository;
use App\Domain\Tenant\Home\Seat\Repositories\Interfaces\IHomeSeatRepository;
use App\Domain\Tenant\Home\Showtime\Repositories\Classes\HomeShowtimeRepository;
use App\Domain\Tenant\Home\Showtime\Repositories\Interfaces\IHomeShowtimeRepository;
use App\Models\Tenant\Booking;
use App\Models\Tenant\Customer;
use App\Models\Tenant\Discount;
use App\Models\Tenant\Payment;
use App\Models\Tenant\Showtime;
use App\Models\Tenant\ShowtimeSeat;
use App\Providers\AppServiceProvider;

class HomeRepositoriesInjector extends AppServiceProvider
{
    public function boot(): void
    {
        $this->app->scoped(IHomeShowtimeRepository::class, function () {
            return new HomeShowtimeRepository(new Showtime);
        });

        $this->app->scoped(IHomeSeatRepository::class, function () {
            return new HomeSeatRepository(new ShowtimeSeat);
        });

        $this->app->scoped(IHomeDiscountRepository::class, function () {
            return new HomeDiscountRepository(new Discount);
        });

        $this->app->scoped(IBookingRepository::class, function () {
            return new BookingRepository(new Booking);
        });

        $this->app->scoped(ICustomerRepository::class, function () {
            return new CustomerRepository(new Customer);
        });

        $this->app->scoped(IPaymentRepository::class, function () {
            return new PaymentRepository(new Payment);
        });
    }
}

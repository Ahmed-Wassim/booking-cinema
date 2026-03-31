<?php

declare(strict_types = 1)
;

use App\Http\Controllers\Tenant\Home\HomeController;
use App\Http\Controllers\Tenant\Home\MovieDetailsController;
use App\Http\Controllers\Tenant\Home\SeatSelectionController;
use App\Http\Controllers\Tenant\Home\BookingConfirmationController;
use App\Http\Controllers\Tenant\Home\ReserveSeatsController;
use App\Http\Controllers\Tenant\Home\BookingController;
use App\Http\Controllers\Tenant\Home\CheckoutPaymentController;
use App\Http\Controllers\Tenant\Home\PaymentCallbackController;
use App\Http\Middleware\Tenant\SetTenantLocale;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;


Route::middleware([
    'api',
    InitializeTenancyByDomain::class ,
    PreventAccessFromCentralDomains::class ,
    SetTenantLocale::class,
])->prefix('api')->group(function () {
    Route::get('/', [HomeController::class , 'index']);
    Route::get('/movies/{id}', [MovieDetailsController::class , 'show']);
    Route::get('/showtimes/{id}/seats', [SeatSelectionController::class , 'show']);
    Route::get('/booking/{id}/success', [BookingConfirmationController::class , 'show']);
    Route::get('/showtimes/{id}/seats', [SeatSelectionController::class , 'seats']);
    Route::post('/reserve-seats', [ReserveSeatsController::class , 'store']);
    Route::post('/bookings', [BookingController::class , 'store']);
    Route::post('/checkout/initiate', [CheckoutPaymentController::class , 'store']);
    Route::post('/checkout/callback', [PaymentCallbackController::class , 'handle']);
});

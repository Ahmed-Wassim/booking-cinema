<?php

declare(strict_types=1);

use App\Http\Controllers\Tenant\Dashboard\Api\Auth\AuthController;
use App\Http\Controllers\Tenant\Dashboard\Api\BranchController;
use App\Http\Controllers\Tenant\Dashboard\Api\HallController;
use App\Http\Controllers\Tenant\Dashboard\Api\HallSectionController;
use App\Http\Controllers\Tenant\Dashboard\Api\MovieController;
use App\Http\Controllers\Tenant\Dashboard\Api\PriceTierController;
use App\Http\Controllers\Tenant\Dashboard\Api\SeatController;
use App\Http\Controllers\Tenant\Dashboard\Api\ShowtimeController;
use App\Http\Controllers\Tenant\Dashboard\Api\UserController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', function () {
        return 'This is your multi-tenant application. The id of the current tenant is '.tenant('id');
    });
});

Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->prefix('api')->group(function () {

    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        Route::apiResource('users', UserController::class);
        Route::apiResource('branches', BranchController::class);
        Route::apiResource('halls', HallController::class);
        Route::apiResource('price-tiers', PriceTierController::class);
        Route::apiResource('hall-sections', HallSectionController::class);
        Route::apiResource('seats', SeatController::class);

        // Movie & Showtime Routes
        Route::get('movies/landlord', [MovieController::class, 'landlordMovies']);
        Route::apiResource('movies', MovieController::class);
        Route::apiResource('showtimes', ShowtimeController::class);
    });
});

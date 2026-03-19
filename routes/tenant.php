<?php

declare(strict_types=1);

use App\Http\Controllers\Tenant\Dashboard\Api\BranchController;
use App\Http\Controllers\Tenant\Dashboard\Api\HallController;
use App\Http\Controllers\Tenant\Dashboard\Api\HallSectionController;
use App\Http\Controllers\Tenant\Dashboard\Api\PriceTierController;
use App\Http\Controllers\Tenant\Dashboard\Api\SeatController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/* |-------------------------------------------------------------------------- | Tenant Routes |-------------------------------------------------------------------------- | | Here you can register the tenant routes for your application. | These routes are loaded by the TenantRouteServiceProvider. | | Feel free to customize them however you want. Good luck! | */

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', function () {
        return 'This is your multi-tenant application. The id of the current tenant is '.tenant('id');
    }
    );
});

Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->prefix('api')->group(function () {
    Route::apiResource('branches', BranchController::class);
    Route::apiResource('halls', HallController::class);
    Route::apiResource('price-tiers', PriceTierController::class);
    Route::apiResource('hall-sections', HallSectionController::class);
    Route::apiResource('seats', SeatController::class);
});

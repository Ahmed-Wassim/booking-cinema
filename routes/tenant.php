<?php

declare(strict_types=1);

use App\Http\Controllers\Tenant\Dashboard\ActivityLogController;
use App\Http\Controllers\Tenant\Dashboard\Api\Auth\AuthController;
use App\Http\Controllers\Tenant\Dashboard\Api\BranchController;
use App\Http\Controllers\Tenant\Dashboard\Api\DiscountController;
use App\Http\Controllers\Tenant\Dashboard\Api\HallController;
use App\Http\Controllers\Tenant\Dashboard\Api\MovieController;
use App\Http\Controllers\Tenant\Dashboard\Api\PaymentController;
use App\Http\Controllers\Tenant\Dashboard\Api\PriceTierController;
use App\Http\Controllers\Tenant\Dashboard\Api\RoleController;
use App\Http\Controllers\Tenant\Dashboard\Api\SeatController;
use App\Http\Controllers\Tenant\Dashboard\Api\ShowtimeController;
use App\Http\Controllers\Tenant\Dashboard\Api\ShowtimeOfferController;
use App\Http\Controllers\Tenant\Dashboard\Api\TicketController;
use App\Http\Controllers\Tenant\Dashboard\Api\UserController;
use App\Http\Middleware\Tenant\SetTenantLocale;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    SetTenantLocale::class,
])->prefix('api/dashboard')->group(function () {

    // ----------------------------------------------------------------------
    // Tenant DASHBOARD API (Admin / Authenticated)
    // ----------------------------------------------------------------------
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::middleware('auth:tenant')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // Protected: returns authenticated user + tenant context (usable by Go microservice via JWT)
        Route::get('/me', function (): JsonResponse {
            /** @var \App\Models\Tenant\User $user */
            $user = auth('tenant')->user();
            return response()->json([
                'user' => $user,
                'tenant_id' => tenant('id'),
                'abilities' => $user->getFrontendAbilities(),
            ]);
        });

        Route::apiResource('users', UserController::class);
        Route::apiResource('branches', BranchController::class);
        Route::apiResource('halls', HallController::class);
        Route::post('halls/{hall}/seats/bulk', [SeatController::class, 'bulkStore']);
        Route::apiResource('price-tiers', PriceTierController::class);
        Route::get('payments', [PaymentController::class, 'index']);
        Route::apiResource('seats', SeatController::class);

        // Movie & Showtime Routes
        Route::get('movies/landlord', [MovieController::class, 'landlordMovies']);
        Route::apiResource('movies', MovieController::class);
        Route::apiResource('showtimes', ShowtimeController::class);

        Route::patch('showtimes/{id}/offer', [ShowtimeOfferController::class, 'update']);
        Route::delete('showtimes/{id}/offer', [ShowtimeOfferController::class, 'destroy']);

        Route::get('permissions', [RoleController::class, 'permissions']);
        Route::apiResource('roles', RoleController::class);

        Route::apiResource('discounts', DiscountController::class);

        // Ticket validation (Staff)
        Route::post('/tickets/validate', [TicketController::class, 'validate'])
            ->name('dashboard.tickets.validate');

        // Activity Logs
        Route::get('activity-logs', [ActivityLogController::class, 'index']);
    });
});

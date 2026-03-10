<?php

use App\Http\Controllers\Landlord\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Landlord\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Landlord\Auth\NewPasswordController;
use App\Http\Controllers\Landlord\Auth\PasswordResetLinkController;
use App\Http\Controllers\Landlord\Auth\RegisteredUserController;
use App\Http\Controllers\Landlord\Auth\VerifyEmailController;
use App\Http\Controllers\Landlord\TenantController;
use App\Http\Controllers\Landlord\UserController;
use Illuminate\Support\Facades\Route;

Route::domain(env('LANDLORD_DOMAIN'))->group(function () {
    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->middleware('guest')
        ->name('register');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('guest')
        ->name('login');

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->middleware('guest')
        ->name('login');

    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->middleware('guest')
        ->name('password.email');

    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->middleware('guest')
        ->name('password.store');

    Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['auth:web', 'signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware(['auth:web', 'throttle:6,1'])
        ->name('verification.send');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->middleware('auth:web')
        ->name('logout');

    Route::get('/dashboard', function () {
        return view('landlord.dashboard.index');
    })->middleware('auth:web')->name('dashboard');

    Route::resource('users', UserController::class)->middleware('auth:web');
    Route::resource('tenants', TenantController::class)->middleware('auth:web');
});

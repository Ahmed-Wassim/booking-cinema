<?php

use App\Http\Controllers\Auth\LandingRegisterController;
use App\Http\Controllers\Landlord\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Landlord\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Landlord\Auth\NewPasswordController;
use App\Http\Controllers\Landlord\Auth\PasswordResetLinkController;
use App\Http\Controllers\Landlord\Auth\RegisteredUserController;
use App\Http\Controllers\Landlord\Auth\VerifyEmailController;
use App\Http\Controllers\Landlord\PlanController;
use App\Http\Controllers\Landlord\RegistrationRequestController;
use App\Http\Controllers\Landlord\TenantController;
use App\Http\Controllers\Landlord\UserController;
use Illuminate\Support\Facades\Route;

Route::domain(env('LANDLORD_DOMAIN'))->group(function () {
    // Central registration is handled in web.php
    Route::get('/register', [LandingRegisterController::class, 'create'])
        ->middleware('guest')
        ->name('register');

    Route::post('/register', [LandingRegisterController::class, 'store'])
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
    }
    )->middleware('auth:web')->name('dashboard');

    Route::resource('users', UserController::class)->middleware('auth:web');
    Route::resource('tenants', TenantController::class)->middleware('auth:web');
    Route::resource('plans', PlanController::class)->middleware('auth:web');

    Route::get('registration-requests', [RegistrationRequestController::class, 'index'])
        ->middleware('auth:web')
        ->name('registration-requests.index');
    Route::post('registration-requests/{registrationRequest}/approve', [RegistrationRequestController::class, 'approve'])
        ->middleware('auth:web')
        ->name('registration-requests.approve');
    Route::post('registration-requests/{registrationRequest}/reject', [RegistrationRequestController::class, 'reject'])
        ->middleware('auth:web')
        ->name('registration-requests.reject');
});

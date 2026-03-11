<?php

use App\Http\Controllers\Auth\LandingRegisterController;
use App\Http\Controllers\Home\LandingController;
use Illuminate\Support\Facades\Route;

Route::domain(env('LANDLORD_DOMAIN'))->group(function () {
    Route::get('/', [LandingController::class , 'index'])->name('landing');

// Route::get('/register', [LandingRegisterController::class, 'create'])->name('register');
// Route::post('/register', [LandingRegisterController::class, 'store']);
});

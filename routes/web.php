<?php

use App\Http\Controllers\Landlord\Home\LandingController;
use Illuminate\Support\Facades\Route;

Route::domain(env('LANDLORD_DOMAIN'))->group(function () {
    Route::get('/', [LandingController::class, 'index'])->name('landing');
});

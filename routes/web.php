<?php

use Illuminate\Support\Facades\Route;

foreach (config('tenancy.central_domains') as $domain) {
    Route::middleware('web')
        ->domain($domain)
        ->group(function () {
            Route::get('/', function () {
                return view('welcome');
            });
        });
}

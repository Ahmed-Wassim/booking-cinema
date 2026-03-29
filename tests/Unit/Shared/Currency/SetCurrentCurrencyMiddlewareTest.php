<?php

use App\Domain\Shared\Currency\Services\CurrentCurrencyService;
use App\Http\Middleware\SetCurrentCurrency;
use Illuminate\Http\Request;

it('sets currency from Currency header', function () {
    $service = new CurrentCurrencyService();
    $middleware = new SetCurrentCurrency($service);

    $request = Request::create('/');
    $request->headers->set('Currency', 'EUR');
    $request->setLaravelSession(session());

    $response = $middleware->handle($request, function ($req) {
        return response('ok');
    });

    expect($service->get())->toBe('EUR');
});

it('sets currency to default when Currency header is missing', function () {
    $service = new CurrentCurrencyService();
    $middleware = new SetCurrentCurrency($service);

    $request = Request::create('/');
    $request->setLaravelSession(session());

    $response = $middleware->handle($request, function ($req) {
        return response('ok');
    });

    expect($service->get())->toBe(config('app.currency', 'USD'));
});

it('handles uppercase and lowercase Currency header', function () {
    $service = new CurrentCurrencyService();
    $middleware = new SetCurrentCurrency($service);

    $request = Request::create('/');
    $request->headers->set('Currency', 'eur');
    $request->setLaravelSession(session());

    $response = $middleware->handle($request, function ($req) {
        return response('ok');
    });

    expect($service->get())->toBe('EUR');
});

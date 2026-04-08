<?php

namespace App\Providers;

use App\Domain\Shared\Currency\Services\CurrentCurrencyService;
use App\Domain\Shared\FileStorage\Contracts\FileStorageServiceInterface;
use App\Domain\Shared\FileStorage\Services\FileStorageService;
use App\Events\ShowtimeChanged;
use App\Listeners\SyncShowtimeToGo;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(FileStorageServiceInterface::class, function () {
            return new FileStorageService('public', 15);
        });

        $this->app->singleton(CurrentCurrencyService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url') . "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        Gate::before(function ($user, $ability) {
            return $user->hasRole('super-admin') ? true : null;
        });

        // Share current currency service with all blade views
        View::share('currentCurrency', $this->app->make(CurrentCurrencyService::class));

        Event::listen(
            ShowtimeChanged::class,
            SyncShowtimeToGo::class
        );
    }
}

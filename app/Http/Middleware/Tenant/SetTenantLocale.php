<?php

namespace App\Http\Middleware\Tenant;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetTenantLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $supportedLocales = array_keys(config('laravellocalization.supportedLocales', []));
        $fallbackLocale = config('app.fallback_locale', 'en');
        $locale = $this->resolveLocaleFromRequest($request, $supportedLocales);

        App::setLocale($locale ?: $fallbackLocale);

        return $next($request);
    }

    private function resolveLocaleFromRequest(Request $request, array $supportedLocales): ?string
    {
        $explicitLocale = $request->header('X-Locale');

        if ($explicitLocale) {
            $normalizedLocale = strtolower(substr($explicitLocale, 0, 2));

            if (in_array($normalizedLocale, $supportedLocales, true)) {
                return $normalizedLocale;
            }
        }

        $acceptLanguage = $request->header('Accept-Language');

        if (! $acceptLanguage) {
            return null;
        }

        foreach (explode(',', $acceptLanguage) as $acceptedLocale) {
            $normalizedLocale = strtolower(substr(trim($acceptedLocale), 0, 2));

            if (in_array($normalizedLocale, $supportedLocales, true)) {
                return $normalizedLocale;
            }
        }

        return null;
    }
}

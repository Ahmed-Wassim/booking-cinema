<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class LocaleController extends Controller
{
    /**
     * Return all supported locales with their metadata (name, native, direction).
     * Used by views to build the language switcher.
     */
    public static function locales(): array
    {
        $current = App::getLocale();

        return collect(LaravelLocalization::getSupportedLocales())
            ->map(function ($data, $code) use ($current) {
                return [
                    'code'      => $code,
                    'native'    => $data['native'],
                    'name'      => $data['name'],
                    'script'    => $data['script'],
                    'rtl'       => $data['script'] === 'Arab',
                    'active'    => $code === $current,
                    'url'       => route('landlord.lang.switch', $code),
                ];
            })
            ->values()
            ->toArray();
    }

    /**
     * Switch the active locale and persist it in the session.
     */
    public function switchLang(string $locale)
    {
        $supported = array_keys(LaravelLocalization::getSupportedLocales());

        if (in_array($locale, $supported)) {
            Session::put('locale', $locale);
            App::setLocale($locale);
        }

        return redirect()->back();
    }
}

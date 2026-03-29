<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Supported Locales
    |--------------------------------------------------------------------------
    | Only the three locales used by this project.
    */
    'supportedLocales' => [
        'en' => ['name' => 'English',  'script' => 'Latn', 'native' => 'English',   'regional' => 'en_GB'],
        'ar' => ['name' => 'Arabic',   'script' => 'Arab', 'native' => 'العربية',  'regional' => 'ar_AE'],
        'fr' => ['name' => 'French',   'script' => 'Latn', 'native' => 'Français',  'regional' => 'fr_FR'],
    ],

    // We use session-based switching, NOT URL prefixes.
    'useAcceptLanguageHeader' => false,

    'hideDefaultLocaleInURL' => false,

    'localesOrder' => ['en', 'fr', 'ar'],

    'localesMapping' => [],

    'utf8suffix' => env('LARAVELLOCALIZATION_UTF8SUFFIX', '.UTF-8'),

    'urlsIgnored' => [],

    'httpMethodsIgnored' => ['POST', 'PUT', 'PATCH', 'DELETE'],
];

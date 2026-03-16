<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PayTabs Configuration
    |--------------------------------------------------------------------------
    |
    | Profile ID and Server Key from your PayTabs merchant dashboard.
    | Region: ARE (UAE), SAU (Saudi Arabia), EGY (Egypt), etc.
    |
    */

    'profile_id'  => env('PAYTABS_PROFILE_ID', ''),
    'server_key'  => env('PAYTABS_SERVER_KEY', ''),
    'region'      => env('PAYTABS_REGION', 'ARE'),
    'currency'    => env('PAYTABS_CURRENCY', 'AED'),
];

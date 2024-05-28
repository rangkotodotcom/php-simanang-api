<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Simanang Mode
    |--------------------------------------------------------------------------
    |
    | By default, use development. Supported Mode: "development", "production"
    |
    */

    'simanang_mode' => env('SIMANANG_MODE', 'development'),

    /*
    |--------------------------------------------------------------------------
    | Simanang Client ID
    |--------------------------------------------------------------------------
    |
    | Client ID from SIMANANG API
    |
    */

    'simanang_client_id' => env('SIMANANG_CLIENT_ID', ''),

    /*
    |--------------------------------------------------------------------------
    | Simanang Client Secret
    |--------------------------------------------------------------------------
    |
    | Client Secret from SIMANANG API
    |
    */

    'simanang_client_secret' => env('SIMANANG_CLIENT_SECRET', ''),
];

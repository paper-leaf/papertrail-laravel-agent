<?php 

return [
    /*
    |--------------------------------------------------------------------------
    | AUTHENTICATION
    |--------------------------------------------------------------------------
    |
    | Allows this site to authenticate with the main PaperTrail application
    |
    */
    'connection' => [
        'inventory_url' => env('PAPERTRAIL_URL', ''),
        'token' => env('PAPERTRAIL_TOKEN', 'not-set'),
    ],

    /*
    |--------------------------------------------------------------------------
    | API ROUTES
    |--------------------------------------------------------------------------
    |
    */

    'route' => [
        'api_version' => 'v1',
        'prefix' => 'papertrail/agent',
    ],
];
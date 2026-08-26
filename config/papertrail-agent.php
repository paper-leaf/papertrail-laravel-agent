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
        'group_name' => 'api',
        'api_version' => 'v1',
        'prefix' => 'papertrail/agent',
    ],

    'actions' => [
        // name => route path
        'test' => 'status',
        'overview' => 'overview',
        'plugins-packages' => 'dependencies',
    ],
];
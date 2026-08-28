<?php 

use Illuminate\Support\Facades\Route;
use PaperLeaf\PaperTrailAgent\Http\Controllers\AgentController;

use PaperLeaf\PaperTrailAgent\Http\Middleware\AuthenticateAgent;

$route_prefix = sprintf('%s/%s/%s', config('papertrail-agent.route.group-name', 'api'), config('papertrail-agent.route.api_version', '0'), config('papertrail-agent.route.prefix', 'agent'));

Route::prefix($route_prefix)
    ->middleware(AuthenticateAgent::class)
    ->group(function () {
        // Test route to allow quick checks if a connection can be made
        Route::get('/status', [AgentController::class, 'status']);

        // Route to get current site state
        Route::get('/overview', [AgentController::class, 'overview']);

        // Route to get all packages + their versions
        Route::get('/dependencies', [AgentController::class, 'dependencies']);
    });
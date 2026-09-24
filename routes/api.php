<?php 

use Illuminate\Support\Facades\Route;
use PaperLeaf\PaperTrailAgent\Http\Controllers\AgentController;

use PaperLeaf\PaperTrailAgent\Http\Middleware\AuthenticateAgent;

$router = isset($this->app->router) ? $this->app->router : app('router');
$route_prefix = sprintf('%s/%s/%s', config('papertrail-agent.route.group-name', 'api'), config('papertrail-agent.route.api_version', '0'), config('papertrail-agent.route.prefix', 'agent'));

$router->group([
    'prefix' => $route_prefix,
    'middleware' => [AuthenticateAgent::class, 'throttle:30,1']
], function ($router) {
    // Test route to allow quick checks if a connection can be made
    Route::get('/status', [AgentController::class, 'status']);

    // Route to get current site state
    Route::get('/overview', [AgentController::class, 'overview']);

    // Route to get all packages + their versions
    Route::get('/dependencies', [AgentController::class, 'dependencies']);
});
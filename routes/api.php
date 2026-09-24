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
    // Use 'uses' with the full class path and @ method indicator for Lumen
    $router->get('/status', [
        'uses' => '\PaperLeaf\PaperTrailAgent\Http\Controllers\AgentController@status'
    ]);

    $router->get('/overview', [
        'uses' => '\PaperLeaf\PaperTrailAgent\Http\Controllers\AgentController@overview'
    ]);

    $router->get('/dependencies', [
        'uses' => '\PaperLeaf\PaperTrailAgent\Http\Controllers\AgentController@dependencies'
    ]);
});
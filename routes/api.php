<?php 

use Illuminate\Support\Facades\Route;
use PaperLeaf\PaperTrailAgent\Http\Controllers\AgentController;

use PaperLeaf\PaperTrailAgent\Http\Middleware\AuthenticateAgent;

$route_prefix = sprintf('api/%s/%s', config('papertrail-agent.route.api_version', '0'), config('papertrail-agent.route.prefix', 'agent'));

Route::prefix($route_prefix)
    ->middleware(AuthenticateAgent::class)
    ->group(function () {
        Route::get('/status', [AgentController::class, 'status']);
    });
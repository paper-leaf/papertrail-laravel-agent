<?php

namespace PaperLeaf\PaperTrailAgent\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateAgent
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check the authentication token
        $token = $request->header('PaperTrail-Token');
        
        // Verify that the token matches the configuration
        $registered_token = config('papertrail-agent.connection.token');

        if($token !== $registered_token) {
            return response()->json([
                'message' => 'Invalid token.',
            ], 401);
        }

        return $next($request);
    }
}
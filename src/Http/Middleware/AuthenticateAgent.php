<?php

namespace PaperLeaf\PaperTrailAgent\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateAgent
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check that a valid token is registered on the site
        $registered_token = (string) config('papertrail-agent.connection.token');

        if ($registered_token === '' || $registered_token === 'not-set') {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        // Check that the passed token matches the registered token
        $token = (string) $request->header('PaperTrail-Token');

        $token = hash('sha256', $token);
        $registered_token = hash('sha256', $registered_token);

        if(!hash_equals($registered_token, $token)) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }
        
        return $next($request);
    }
}
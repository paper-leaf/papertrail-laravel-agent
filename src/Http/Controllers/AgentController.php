<?php

namespace PaperLeaf\PaperTrailAgent\Http\Controllers;

use Illuminate\Http\JsonResponse;

class AgentController
{
    public function status(): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'agent' => 'papertrail',
        ]);
    }
}
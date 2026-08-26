<?php

namespace PaperLeaf\PaperTrailAgent\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

use PaperLeaf\PaperTrailAgent\Models\ComposerPackage;

class AgentController
{
    /**
     * Allow the connection to be tested
     */
    public function status(): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'agent' => 'papertrail-laravel',
        ]);
    }

    /**
     * Send an overview of the entire site
     */
    public function overview(): JsonResponse
    {
        $response = [
            'agent' => 'papertrail-laravel',
            'site' => [
                'name' => config('app.name'),
                'type' => 'laravel',
            ],
            'runtime' => [
                'php' => [
                    'version' => Str::start(phpversion(), 'v')
                ],
            ],
            'platform' => [
                'name' => 'laravel/framework',
                'version' => optional(ComposerPackage::firstWhere('name', 'laravel/framework'))->version,
            ],
            'frameworks' => [
                [
                    'name' => 'filament/filament',
                    'version' => optional(ComposerPackage::firstWhere('name', 'filament/filament'))->version,
                ],
                [
                    'name' => 'livewire/livewire',
                    'version' => optional(ComposerPackage::firstWhere('name', 'livewire/livewire'))->version,
                ],
            ],
        ];

        return response()->json($response); 
    }

    /**
     * Send a list of all installed dependencies (packages)
     */
    public function dependencies(): JsonResponse
    {
        // Get all packages that are user installed. Skip package to package dependencies for now.
        $packages = ComposerPackage::whereIn('install_type', ['require', 'require_dev'])->get();
        
        // Format the response
        $formatted_packages = [];
        foreach($packages as $package) {
            $formatted_packages[] = [
                'name' => $package->name,
                'version' => $package->version,
            ];
        }

        $response = [
            'type' => 'composer-packages',
            'packages' => $formatted_packages,
        ];

        return response()->json($response); 
    }
}
<?php 

namespace PaperLeaf\PaperTrailAgent\Providers;

use Illuminate\Support\ServiceProvider;

class PaperTrailAgentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/papertrail-agent.php',
            'papertrail-agent',
        );
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
    }
}
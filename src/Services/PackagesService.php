<?php

namespace PaperLeaf\PaperTrailAgent\Services;

use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Composer\InstalledVersions;

class PackagesService
{
    /**
     * Get the version of a specific package installed via Composer
     * 
     * @param string $package_name
     * @return string
     */
    public function composerVersion($package_name)
    {
        return $this->checkPackage($package_name)['version'];
    }

    /**
     * Check if a composer package is even installed
     * 
     * @param string $package_name
     * @return bool
     */
    public function composerInstalled($package_name)
    {
        return $this->checkPackage($package_name)['installed'];
    }

    /**
     * Run an Artisan command
     * 
     * @param string $command
     * @param bool (optional) $return_output
     * @return string
     */
    public function runArtisan($command, $return_output = false)
    {
        $exit_code = Artisan::call($command);

        if($return_output) {
            $output = Artisan::output();
            return $output;
        }
    }

    /**
     * Run a bash/shell command
     * 
     * @param string $command
     * @return string
     */
    public function runSystemCommand($command)
    {
        // 1. Run a basic system command
        $result = Process::run($command);

        // 2. Check if the process completed successfully
        if ($result->successful()) {
            return $result->output();
        }

        return $result->errorOutput();
    }

    /**
     * Check a composer package
     * 
     * @param string $package_name
     * @return array
     */
    public function checkPackage($package_name)
    {
        // Check if the package is installed anywhere in the project
        if (InstalledVersions::isInstalled($package_name)) {
            return [
                'installed' => true,
                'version' => InstalledVersions::getPrettyVersion($package_name),
            ];
        }

        return [
            'installed' => false,
            'version' => null,
        ];
    }

    /**
     * Get the description of a composer package
     * 
     * @param string $package_name
     * @return string
     */
    public function composerDescription($package_name)
    {
        // Search for the package that was provided
        $package = $this->installedPackages()->firstWhere('name', $package_name);
        if (!isset($package) && !isset($package->description)) {
            return '';
        }

        // Return the description!
        return $package->description;
    }

    /**
     * Get the list of all Composer packages that are installed
     * 
     * @return Collection
     */
    public function installedPackages()
    {
        $installed_path = base_path('/vendor/composer/installed.json');
        if(!File::exists($installed_path)) {
            return collect([]);
        }

        $packages = optional(json_decode(file_get_contents($installed_path)))->packages;
        if (!isset($packages)) {
            return collect([]);
        }

        return collect($packages);
    }

    /**
     * Get the list of packages that were user installed 
     * 
     * @return Collection
     */
    public function userInstalledPackages()
    {
        $composer_path = base_path('composer.json');
        if(!File::exists($composer_path)) {
            return collect([]);
        }

        $composer_data = collect(json_decode(file_get_contents($composer_path)));

        $require = collect($composer_data->get('require', []))
                    ->mapWithKeys(function($version, $package_name) {
                        return [$package_name => 'require'];
                    });

        $require_dev = collect($composer_data->get('require-dev', []))
                        ->mapWithKeys(function($version, $package_name) {
                            return [$package_name => 'require-dev'];
                        });

        $installed = $require_dev->merge($require);
        return $installed;
    }

    /*********************************************
     * PACKAGE STATUSES
     *********************************************/

    /**
     * Check if a package is installed
     * 
     * @param string $package_name
     * @return bool
     */
    public function isInstalled($package_type, $package_name) {
        switch($package_type) {
            case 'composer': 
                return $this->composerInstalled($package_name);
                break; 

            default: 
                return false;
                break;
        }        
    }

    /*********************************************
     * SPECIFIC PACKAGE CHECKS
     *********************************************/

    /**
     * Get the current status of Horizon
     * 
     * @return string
     */
    public function horizonStatus()
    {
        try {
            $output = $this->runArtisan('horizon:status', true);
    
            if(Str::contains($output, 'INFO')) {
                return 'Active';
            }
        }
        catch(\Exception $e) {
            Log::error($e->getMessage());
        }
            
        return 'Inactive';
    }

    /**
     * Get the current status of a package that's controlled via the .env
     * .e.g. Laravel Debug Bar
     * 
     * @param string $env_key
     * @return string
     */
    public function envStatus($env_key)
    {
        $value = env($env_key, false);
        if($value) {
            return 'Active';
        }

        return 'Inactive';
    }
}

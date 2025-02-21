<?php

namespace Tipusultan\Bkash;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class BkashServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Register publishable resources
        $this->registerPublishables();

        // Load views and migrations
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'bkash');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }

    protected function registerPublishables()
    {
        if ($this->app->runningInConsole()) {
            // Publish Config
            $this->publishes([
                __DIR__ . '/config/bkash.php' => config_path('bkash.php')
            ], 'bkash-config');

            // Publish Controller
            $this->publishes([
                __DIR__ . '/Controllers/BkashController.php' => app_path('Http/Controllers/BkashController.php')
            ], 'bkash-controller');

            // Publish Views
            $this->publishes([
                __DIR__ . '/resources/views' => resource_path('views/')
            ], 'bkash-views');

            // Publish Service
            $this->publishes([
                __DIR__ . '/Services/BkashService.php' => app_path('Services/BkashService.php')
            ], 'bkash-service');
        }
    }

    public function register()
    {
        $configPath = __DIR__ . '/config/bkash.php';
        if (file_exists($configPath)) {
            $this->mergeConfigFrom($configPath, 'bkash');
        }
    }
}
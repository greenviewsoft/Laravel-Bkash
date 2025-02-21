<?php

namespace Tipusultan\Bkash;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class BkashServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishFiles();
        $this->setupRoutes();
        
        // Load views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'bkash');
        
        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    protected function publishFiles()
    {
        // Publish config
        $this->publishes([
            __DIR__ . '/../config/bkash.php' => config_path('bkash.php'),
        ], 'bkash-config');

        // Publish Controller
        $this->publishes([
            __DIR__ . '/Controllers/BkashController.php' => app_path('Http/Controllers/BkashController.php'),
        ], 'bkash-controller');

        // Publish Service
        $this->publishes([
            __DIR__ . '/Services/BkashService.php' => app_path('Services/BkashService.php'),
        ], 'bkash-service');

        // Publish views
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/bkash'),
        ], 'bkash-views');

        // Publish migrations
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'bkash-migrations');

        // Ensure files exist
        $this->ensureFilesExist();
    }

    protected function ensureFilesExist()
    {
        // Ensure config exists
        if (!file_exists(config_path('bkash.php'))) {
            $this->copyFile(
                __DIR__ . '/../config/bkash.php', 
                config_path('bkash.php')
            );
        }

        // Ensure controller exists
        if (!file_exists(app_path('Http/Controllers/BkashController.php'))) {
            $this->copyFile(
                __DIR__ . '/Controllers/BkashController.php',
                app_path('Http/Controllers/BkashController.php')
            );
        }

        // Ensure service exists
        if (!file_exists(app_path('Services/BkashService.php'))) {
            $this->copyFile(
                __DIR__ . '/Services/BkashService.php',
                app_path('Services/BkashService.php')
            );
        }

        // Ensure views directory exists
        $viewsPath = resource_path('views/bkash');
        if (!File::exists($viewsPath)) {
            File::makeDirectory($viewsPath, 0755, true);
            
            // Copy all view files
            foreach (['pay', 'success', 'fail', 'refund'] as $view) {
                $this->copyFile(
                    __DIR__ . "/../resources/views/{$view}.blade.php",
                    "{$viewsPath}/{$view}.blade.php"
                );
            }
        }
    }

    protected function setupRoutes()
    {
        $routesPath = base_path('routes/web.php');
        if (!File::exists($routesPath)) {
            return;
        }

        $bkashRoutes = <<<'ROUTES'

// bKash Payment Routes
Route::middleware(['web', 'auth'])->group(function () {
    Route::prefix('bkash')->group(function () {
        Route::get('/', [\App\Http\Controllers\BkashController::class, 'payment'])->name('url-pay');
        Route::post('/create', [\App\Http\Controllers\BkashController::class, 'createPayment'])->name('url-create');
        Route::get('/callback', [\App\Http\Controllers\BkashController::class, 'callback'])->name('url-callback');
        Route::get('/refund', [\App\Http\Controllers\BkashController::class, 'getRefund'])->name('url-get-refund');
        Route::post('/refund', [\App\Http\Controllers\BkashController::class, 'refundPayment'])->name('url-post-refund');
    });
});

ROUTES;

        $content = File::get($routesPath);
        if (!str_contains($content, 'bKash Payment Routes')) {
            File::append($routesPath, $bkashRoutes);
        }
    }

    protected function copyFile($source, $destination)
    {
        $destinationDir = dirname($destination);
        if (!File::exists($destinationDir)) {
            File::makeDirectory($destinationDir, 0755, true);
        }

        if (File::exists($source)) {
            File::copy($source, $destination);
        }
    }

    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(
            __DIR__ . '/../config/bkash.php', 'bkash'
        );
    }
}
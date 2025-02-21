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
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'bkash');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Setup routes
        $this->setupRoutes();
    }

    protected function registerPublishables()
    {
        if ($this->app->runningInConsole()) {
            // Publish config
            $this->publishes([
                __DIR__ . '/../config/bkash.php' => config_path('bkash.php')
            ], 'bkash-config');

            // Publish Controller
            $this->publishes([
                __DIR__ . '/../src/Controllers/BkashController.php' => app_path('Http/Controllers/BkashController.php')
            ], 'bkash-controller');

            // Publish Views
            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/bkash')
            ], 'bkash-views');
        }
    }

    public function register()
    {
        $configPath = __DIR__ . '/../config/bkash.php';
        if (file_exists($configPath)) {
            $this->mergeConfigFrom($configPath, 'bkash');
        }
    }

    protected function setupRoutes()
    {
        $routesPath = base_path('routes/web.php');
        if (!File::exists($routesPath)) {
            return;
        }

        $bkashRoutes = <<<'ROUTES'

// bKash Payment Routes (Auto-Added)
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

        // Avoid duplicate route entries
        if (!str_contains(File::get($routesPath), 'bKash Payment Routes')) {
            File::append($routesPath, PHP_EOL . $bkashRoutes);
        }
    }
}

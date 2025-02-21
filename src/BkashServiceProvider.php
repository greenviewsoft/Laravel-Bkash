<?php

namespace Tipusultan\Bkash;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class BkashServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // First register the publishable resources
        $this->registerPublishables();
        
        // Then load views and migrations
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'bkash');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        
        // Finally, setup routes
        $this->setupRoutes();
    }

    protected function registerPublishables()
    {
        if ($this->app->runningInConsole()) {
            // Config
            $this->publishes([
                __DIR__ . '/../config/bkash.php' => config_path('bkash.php')
            ], 'bkash-config');

            // Views
            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/bkash')
            ], 'bkash-views');

            // Controller
            $this->publishes([
                __DIR__ . '/Controllers/BkashController.php' => app_path('Http/Controllers/BkashController.php')
            ], 'bkash-controller');
        }
    }

    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(__DIR__ . '/../config/bkash.php', 'bkash');
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

        if (!str_contains(File::get($routesPath), 'bKash Payment Routes')) {
            File::append($routesPath, $bkashRoutes);
        }
    }
}
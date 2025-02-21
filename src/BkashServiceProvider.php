<?php

namespace Tipusultan\Bkash;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class BkashServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Auto-Publish Configuration File
        $this->publishes([
            __DIR__ . '/../config/bkash.php' => config_path('bkash.php'),
        ], 'bkash-config');

        // Ensure configuration file is copied automatically if not exists
        if (!file_exists(config_path('bkash.php'))) {
            copy(__DIR__ . '/../config/bkash.php', config_path('bkash.php'));
        }

        // Auto-Publish Controller File
        $this->publishes([
            __DIR__ . '/../src/Controllers/BkashController.php' => app_path('Http/Controllers/BkashController.php'),
        ], 'bkash-controller');

        // Auto-Publish Views
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/bkash'),
        ], 'bkash-views');

        // Ensure Routes Are Appended Safely
        $this->appendRoutesToWebFile();

        // Load Views Automatically
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'bkash');

        // Load Migrations if Available
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    public function register()
    {
        $configPath = __DIR__ . '/../config/bkash.php';
        if (file_exists($configPath)) {
            $this->mergeConfigFrom($configPath, 'bkash');
        }
    }

    /**
     * Safely Append bKash Routes to `web.php` Without Removing Existing Routes
     */
    protected function appendRoutesToWebFile()
    {
        $webRoutesPath = base_path('routes/web.php');
        $bkashRoutes = <<<ROUTES

// bKash Payment Routes (Auto-Added by Laravel bKash Package)
Route::get('/bkash', [\App\Http\Controllers\BkashController::class, 'payment'])->name('url-pay');
Route::post('/bkash/create', [\App\Http\Controllers\BkashController::class, 'createPayment'])->name('url-create');
Route::get('/bkash/callback', [\App\Http\Controllers\BkashController::class, 'callback'])->name('url-callback');
Route::get('/bkash/refund', [\App\Http\Controllers\BkashController::class, 'getRefund'])->name('url-get-refund');
Route::post('/bkash/refund', [\App\Http\Controllers\BkashController::class, 'refundPayment'])->name('url-post-refund');

ROUTES;

        // Ensure `web.php` exists
        if (!File::exists($webRoutesPath)) {
            return;
        }

        // Get existing content of `web.php`
        $webRoutesContent = File::get($webRoutesPath);

        // Prevent duplicate insertion of bKash routes
        if (!str_contains($webRoutesContent, 'bKash Payment Routes')) {
            // Append routes **without removing existing content**
            File::append($webRoutesPath, PHP_EOL . $bkashRoutes);
        }
    }
}

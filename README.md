# Laravel bKash Payment Gateway

A simple and efficient bKash Payment Gateway integration package for Laravel 10 & 11.

## Features

- Easy bKash Payment Integration
- Supports both Sandbox & Live Mode
- Handles Refunds & Callbacks
- Compatible with Laravel 10 & 11

## Installation

### 1. Install Package

```bash
composer require tipusultan/laravel-bkash
```

### 2. Publish Package Files

```bash
php artisan vendor:publish --tag=bkash
```

### 3. Configure Environment Variables

Add these to your `.env` file:

```env
SANDBOX=true
BKASH_USERNAME='your_username'
BKASH_PASSWORD='your_password'
BKASH_APP_KEY='your_app_key'
BKASH_APP_SECRET='your_app_secret'
```

## Package Structure

```
📂 Laravel Project
├── 📂 resources
│   └── 📂 views/bkash
│       ├── pay.blade.php
│       ├── success.blade.php
│       ├── fail.blade.php
│       ├── refund.blade.php
├── 📂 app
│   ├── 📂 Http/Controllers/Bkash
│   └── 📂 Services/Bkash
├── 📂 routes
│   └── bkash.php
├── 📂 config
│   └── bkash.php
```

## Usage

### Payment Routes

```php
Route::get('/bkash', [BkashController::class, 'payment'])->name('url-pay');
Route::post('/bkash/create', [BkashController::class, 'createPayment'])->name('url-create');
Route::get('/bkash/callback', [BkashController::class, 'callback'])->name('url-callback');
```

### Refund Routes

```php
Route::get('/bkash/refund', [BkashController::class, 'getRefund'])->name('url-get-refund');
Route::post('/bkash/refund', [BkashController::class, 'refundPayment'])->name('url-post-refund');
```

### Available Views

| View | Path |
|------|------|
| Payment Form | resources/views/bkash/pay.blade.php |
| Success Page | resources/views/bkash/success.blade.php |
| Failure Page | resources/views/bkash/fail.blade.php |
| Refund Form | resources/views/bkash/refund.blade.php |

## Important Notice

⚠️ This bKash Payment Gateway package is for educational & testing purposes only.
Before using it in production, thoroughly test all features and report any issues.

## Contributing

Found a bug? Open an issue in the GitHub repository.
Contributions are welcome! Feel free to submit pull requests.

## License

This package is licensed under the MIT License.

---

🌟 If you find this package helpful, please star this repository!
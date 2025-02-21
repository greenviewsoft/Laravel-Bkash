<?php

use TipuSultan\Bkash\Http\Controllers\BkashController;
use Illuminate\Support\Facades\Route;

// bKash Payment Routes Musbte Use Auth Middleware User Must Be Logged In

    Route::get('/bkash', [App\Http\Controllers\BkashController::class, 'payment'])->name('url-pay');
    Route::post('/bkash/create', [App\Http\Controllers\BkashController::class, 'createPayment'])->name('url-create');
    Route::get('/bkash/callback', [App\Http\Controllers\BkashController::class, 'callback'])->name('url-callback');
    Route::get('/bkash/refund', [App\Http\Controllers\BkashController::class, 'getRefund'])->name('url-get-refund');
    Route::post('/bkash/refund', [App\Http\Controllers\BkashController::class, 'refundPayment'])->name('url-post-refund');

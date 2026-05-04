<?php

use App\Http\Controllers\Filament\LanguageSwitchController;
use App\Http\Controllers\MidtransWebhookController;
use Illuminate\Support\Facades\Route;

// Customer Frontend Routes
Route::middleware(['web', 'set-locale', 'track-visitor'])->group(function () {
    require __DIR__.'/customer.php';
});

// Midtrans Webhook (no CSRF)
Route::post('/webhook/midtrans', [MidtransWebhookController::class, 'handle'])
    ->name('webhook.midtrans')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// Admin Language Switch Route
Route::get('/admin/language/switch/{locale}', [LanguageSwitchController::class, 'switch'])
    ->name('filament.admin.language.switch')
    ->middleware(['web', 'auth']);

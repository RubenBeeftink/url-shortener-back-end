<?php

use App\Http\Controllers\Api\ShortUrlController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::middleware(['auth:sanctum'])
    ->group(function() {
        Route::prefix('short-url')
            ->name('short-url.')
            ->group(function() {
                Route::get('/', [ShortUrlController::class, 'index'])->name('index');
                Route::get('/{shortUrl}', [ShortUrlController::class, 'show'])->name('show');
                Route::post('/', [ShortUrlController::class, 'store'])->name('store');
                Route::patch('/{shortUrl}', [ShortUrlController::class, 'update'])->name('update');
                Route::delete('/{shortUrl}', [ShortUrlController::class, 'destroy'])->name('destroy');
            });
    });

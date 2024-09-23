<?php

use App\Http\Controllers\RedirectController;
use Illuminate\Support\Facades\Route;


Route::get('/{hash}', [RedirectController::class, 'index'])->name('index');

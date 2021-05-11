<?php

use App\Api\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::get('/me', [AuthController::class, 'me'])->name('auth.me')->middleware('auth:api');
});

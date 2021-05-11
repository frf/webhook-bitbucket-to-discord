<?php

use App\Api\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('users')->middleware(['auth:api'])->group(function () {

    Route::get('/{id}', [UserController::class, 'show'])
        ->name('users.show')
        ->where('id', '[0-9]+')
        ->middleware(['role:user']);

    Route::post('/', [UserController::class, 'create'])
        ->name('users.create')
        ->middleware(['role:sa']);

    Route::patch('/{user:id}', [UserController::class, 'update'])
        ->name('users.update')
        ->where('id', '[0-9]+');
});

<?php

use App\Api\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::prefix('webhooks')->middleware(['auth:api','user_context'])
    ->group(function () {
        Route::get('/', [WebhookController::class, 'index'])
            ->name('webhooks.index');

        Route::get('/{id}', [WebhookController::class, 'show'])
            ->name('webhooks.show')
            ->where('id', '[0-9]+');

        Route::post('/', [WebhookController::class, 'create'])
            ->name('webhooks.create');

        Route::patch('/{webhook:id}', [WebhookController::class, 'update'])
            ->name('webhooks.update')
            ->where('id', '[0-9]+');
});

Route::post('/webhook-message/{id}', [WebhookController::class, 'webhookMessage'])
    ->name('webhooks.webhookMessage');

<?php

use App\Api\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::prefix('webhooks')->middleware(['auth:api','user_context'])
    ->group(function () {
        Route::get('/', [WebhookController::class, 'index'])
            ->name('webhooks.index');

        Route::get('/{id}', [WebhookController::class, 'show'])
            ->name('webhooks.show');

        Route::get('/{id}/histories', [WebhookController::class, 'histories'])
            ->name('webhooks.histories');

        Route::get('/histories/{id}', [WebhookController::class, 'historieShow'])
            ->name('webhooks.histories.show');

        Route::post('/', [WebhookController::class, 'create'])
            ->name('webhooks.create');

        Route::patch('/{id}', [WebhookController::class, 'update'])
            ->name('webhooks.update');
});

Route::post('/webhook-message/{id}', [WebhookController::class, 'webhookMessage'])
    ->name('webhooks.webhookMessage');

<?php

namespace Domain\Webhook\Actions;

use App\Api\Resources\WebhookResource;
use App\Exceptions\ResourceNotFoundException;
use Domain\Webhook\Bags\WebhookBag;
use Domain\Webhook\Models\Webhook;
use Domain\Webhook\Repositories\WebhookRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SendMessageWebhookAction
{
    private WebhookRepository $webhookRepository;

    public function __construct(
        WebhookRepository $webhookRepository
    ) {
        $this->webhookRepository = $webhookRepository;
    }

    public function execute($id): WebhookResource
    {
        $cacheKey = 'webhook:message:'.md5($id);

//        return Cache::tags(['webhook_message'])
//            ->remember($cacheKey, 240, function () {
                $webhook = $this->webhookRepository->findByHash($id);

                Log::info('webhook', request()->all());

                if (!$webhook) {
                    throw new ResourceNotFoundException();
                }

                return WebhookResource::make($webhook);
//        });
    }
}

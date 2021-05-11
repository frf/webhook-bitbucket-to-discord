<?php

namespace Domain\Webhook\Actions;

use App\Api\Resources\WebhookResource;
use Domain\User\Bags\MailUserBag;
use Domain\User\Bags\UserBag;
use Domain\User\Repositories\UserRepository;
use Domain\Webhook\Bags\WebhookBag;
use Domain\Webhook\Models\Webhook;
use Domain\Webhook\Repositories\WebhookRepository;
use Illuminate\Support\Facades\Hash;

class CreateWebhookAction
{
    private WebhookRepository $webhookRepository;

    public function __construct(
        WebhookRepository $webhookRepository
    ) {
        $this->webhookRepository = $webhookRepository;
    }

    public function execute(WebhookBag $webhookBag): Webhook
    {
        $data = $webhookBag->attributes();

        $data['my_webhook'] = $data['webhook'];

        if (isset($data['webhook'])) {
            unset($data['webhook']);
        }

        $webhook = $this->webhookRepository->create($data);
        $webhook->webhook_hash = $this->webhookRepository
            ->generateWebhook($webhook->id, $webhook->user_id);
        $webhook->save();

        return $this->webhookRepository->find($webhook->id);
    }
}

<?php

namespace Domain\Webhook\Actions;

use App\Api\Resources\WebhookResource;
use App\Exceptions\ResourceNotFoundException;
use Domain\User\Bags\MailUserBag;
use Domain\User\Bags\UserBag;
use Domain\User\Repositories\UserRepository;
use Domain\Webhook\Bags\WebhookBag;
use Domain\Webhook\Models\Webhook;
use Domain\Webhook\Repositories\WebhookRepository;
use Illuminate\Support\Facades\Hash;

class UpdateWebhookAction
{
    private WebhookRepository $webhookRepository;

    public function __construct(
        WebhookRepository $webhookRepository
    ) {
        $this->webhookRepository = $webhookRepository;
    }

    public function execute(WebhookBag $webhookBag, $id): Webhook
    {
        $data = $webhookBag->attributes();

        $dataUpdate['my_webhook'] = $data['webhook'];

        $webhook = $this->webhookRepository->findByHash($id);

        if (!$webhook) {
            throw new ResourceNotFoundException();
        }

        $this->webhookRepository->update($dataUpdate, $webhook->id);

        return $this->webhookRepository->find($webhook->id);
    }
}

<?php

namespace App\Api\Resources;

use Domain\User\Bags\PatientBag;
use Domain\Webhook\Bags\WebhookBag;
use Domain\Webhook\Models\Webhook;
use Domain\Webhook\Repositories\WebhookRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class WebhookResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var WebhookBag $this */
        return [
//            'id' => $this->getKey(),
            'webhook' => Webhook::URL_WEBHOOK . $this->webhook_hash,
            'content' => $this->content,
            'application' => $this->application,
            'my_webhook' => $this->my_webhook,
            'created_at' => $this->created_at,
        ];
    }
}

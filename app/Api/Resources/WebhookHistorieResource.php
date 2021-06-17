<?php

namespace App\Api\Resources;

use Domain\User\Bags\PatientBag;
use Domain\Webhook\Bags\WebhookBag;
use Domain\Webhook\Models\Webhook;
use Domain\Webhook\Repositories\WebhookRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class WebhookHistorieResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->getKey(),
            'content' => $this->content,
            'content_original' => $this->content_original,
            'created_at' => $this->created_at,
        ];
    }
}

<?php

namespace Domain\Webhook\Repositories;

use App\Repositories\Repository;
use Domain\Webhook\Models\Webhook;
use Domain\Webhook\Models\WebhookHistorie;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

class WebhookHistorieRepository extends Repository
{
    public function model()
    {
        return WebhookHistorie::class;
    }

    public function findByWebhook(Webhook $webhook)
    {
        $this->applyCriteria();
        return QueryBuilder::for($this->model)
            ->where('webhook_id', $webhook->getKey())
            ->paginate($this->perPage);
    }

}

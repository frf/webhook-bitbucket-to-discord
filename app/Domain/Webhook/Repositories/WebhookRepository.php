<?php

namespace Domain\Webhook\Repositories;

use App\Repositories\Repository;
use Domain\Webhook\Models\Webhook;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

class WebhookRepository extends Repository
{
    public function model()
    {
        return Webhook::class;
    }

    public function all($columns = array('*'))
    {
        $this->applyCriteria();
        return QueryBuilder::for($this->model)
            ->where('user_id', Auth::user()->getAuthIdentifier())
            ->paginate();
    }

    public function find($id, $columns = array('*'))
    {
        $this->applyCriteria();
        return QueryBuilder::for($this->model)
            ->find($id, $columns);
    }

    public function findByHash($id)
    {
        $this->applyCriteria();
        return QueryBuilder::for($this->model)
            ->allowedIncludes(['histories'])
            ->where('webhook_hash', $id)
            ->first();
    }

    public function findHistoriesByHash($id)
    {
        $this->applyCriteria();
        return QueryBuilder::for($this->model)
            ->allowedIncludes(['histories'])
            ->where('webhook_hash', $id)
            ->first();
    }

    public function create(array $data): Webhook
    {
        return parent::create($data);
    }

    public function update(array $data, $id, $attribute = "id")
    {
        return parent::update($data, $id, $attribute);
    }

    public function generateWebhook(int $id, int $user_id) : string
    {
        return md5($id. '|' . $user_id);
    }
}

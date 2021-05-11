<?php

namespace App\Api\Controllers;

use App\Api\Requests\CreateWebhookRequest;
use App\Api\Requests\UpdateWebhookRequest;
use App\Api\Resources\WebhookResource;
use Domain\Webhook\Actions\CreateWebhookAction;
use Domain\Webhook\Actions\SendMessageWebhookAction;
use Domain\Webhook\Actions\UpdateWebhookAction;
use Domain\Webhook\Bags\WebhookBag;
use Domain\Webhook\Repositories\WebhookRepository;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    private CreateWebhookAction $createWebhookAction;
    private WebhookRepository $webhookRepository;
    private SendMessageWebhookAction $messageWebhookAction;
    private UpdateWebhookAction $updateWebhookAction;

    public function __construct(
        CreateWebhookAction $createWebhookAction,
        WebhookRepository $webhookRepository,
        SendMessageWebhookAction $messageWebhookAction,
        UpdateWebhookAction $updateWebhookAction
    ) {
        $this->createWebhookAction = $createWebhookAction;
        $this->webhookRepository = $webhookRepository;
        $this->messageWebhookAction = $messageWebhookAction;
        $this->updateWebhookAction = $updateWebhookAction;
    }

    public function index()
    {
        return WebhookResource::collection($this->webhookRepository->all());
    }

    public function create(CreateWebhookRequest $createWebhookRequest): WebhookResource
    {
        $webhookBag = WebhookBag::fromRequest($createWebhookRequest->validated());
        return WebhookResource::make($this->createWebhookAction->execute($webhookBag));
    }

    public function update(UpdateWebhookRequest $updateWebhookRequest, $id): WebhookResource
    {
        $webhookBag = WebhookBag::fromRequest($updateWebhookRequest->validated());
        return WebhookResource::make($this->updateWebhookAction->execute($webhookBag, $id));
    }

    public function webhookMessage($id): WebhookResource
    {
        return $this->messageWebhookAction->execute($id);
    }

    public function show($id)
    {
        return $id;
    }
}

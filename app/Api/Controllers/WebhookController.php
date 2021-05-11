<?php

namespace App\Api\Controllers;

use App\Api\Requests\CreateWebhookRequest;
use App\Api\Resources\WebhookResource;
use Domain\Webhook\Actions\CreateWebhookAction;
use Domain\Webhook\Actions\SendMessageWebhookAction;
use Domain\Webhook\Bags\WebhookBag;
use Domain\Webhook\Repositories\WebhookRepository;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    private CreateWebhookAction $createWebhookAction;
    private WebhookRepository $webhookRepository;
    private SendMessageWebhookAction $messageWebhookAction;

    public function __construct(
        CreateWebhookAction $createWebhookAction,
        WebhookRepository $webhookRepository,
        SendMessageWebhookAction $messageWebhookAction
    ) {
        $this->createWebhookAction = $createWebhookAction;
        $this->webhookRepository = $webhookRepository;
        $this->messageWebhookAction = $messageWebhookAction;
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
    public function webhookMessage($id): WebhookResource
    {
        return $this->messageWebhookAction->execute($id);
    }

    public function show($id)
    {
        return $id;
    }
}

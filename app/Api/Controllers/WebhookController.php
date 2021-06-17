<?php

namespace App\Api\Controllers;

use App\Api\Requests\CreateWebhookRequest;
use App\Api\Requests\UpdateWebhookRequest;
use App\Api\Resources\WebhookHistorieResource;
use App\Api\Resources\WebhookResource;
use App\Exceptions\ResourceNotFoundException;
use Domain\Webhook\Actions\CreateWebhookAction;
use Domain\Webhook\Actions\SendMessageWebhookAction;
use Domain\Webhook\Actions\UpdateWebhookAction;
use Domain\Webhook\Bags\WebhookBag;
use Domain\Webhook\Repositories\WebhookHistorieRepository;
use Domain\Webhook\Repositories\WebhookRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class WebhookController extends Controller
{
    private CreateWebhookAction $createWebhookAction;
    private WebhookRepository $webhookRepository;
    private SendMessageWebhookAction $messageWebhookAction;
    private UpdateWebhookAction $updateWebhookAction;
    private WebhookHistorieRepository $webhookHistorieRepository;

    public function __construct(
        CreateWebhookAction $createWebhookAction,
        WebhookRepository $webhookRepository,
        WebhookHistorieRepository $webhookHistorieRepository,
        SendMessageWebhookAction $messageWebhookAction,
        UpdateWebhookAction $updateWebhookAction
    ) {
        $this->createWebhookAction = $createWebhookAction;
        $this->webhookRepository = $webhookRepository;
        $this->messageWebhookAction = $messageWebhookAction;
        $this->updateWebhookAction = $updateWebhookAction;
        $this->webhookHistorieRepository = $webhookHistorieRepository;
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
        $cacheKey = 'webhook:show:'.md5($id);

         return Cache::tags(['webhook_show'])
            ->remember($cacheKey, 1, function () use ($id) {
                $webhook = $this->webhookRepository->findByHash($id);

                if (!$webhook) {
                    throw new ResourceNotFoundException();
                }

                return WebhookResource::make($webhook);
            });
    }

    public function histories($id)
    {
        $cacheKey = 'webhook:show:'.md5($id);

         return Cache::tags(['webhook_show'])
            ->remember($cacheKey, 1, function () use ($id) {
                $webhook = $this->webhookRepository->findByHash($id);

                if (!$webhook) {
                    throw new ResourceNotFoundException();
                }

                return WebhookHistorieResource::collection($this->webhookHistorieRepository->findByWebhook($webhook));
            });
    }

    public function historieShow($id)
    {
        $cacheKey = 'webhook_historie:show:'.md5($id);

        return Cache::tags(['webhook_historie_show'])
            ->remember($cacheKey, 1, function () use ($id) {
                $webhook = $this->webhookHistorieRepository->find($id);

                if (!$webhook) {
                    throw new ResourceNotFoundException();
                }

                return WebhookHistorieResource::make($webhook);
            });
    }
}

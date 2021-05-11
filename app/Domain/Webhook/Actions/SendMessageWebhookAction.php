<?php

namespace Domain\Webhook\Actions;

use App\Api\Resources\WebhookResource;
use App\Exceptions\ResourceNotFoundException;
use Carbon\Carbon;
use Domain\Webhook\Bags\DiscordBag;
use Domain\Webhook\Repositories\WebhookRepository;
use Frf\DiscordNotification\Services\DiscordService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SendMessageWebhookAction
{
    private WebhookRepository $webhookRepository;
    private DiscordService $discordService;

    public function __construct(
        WebhookRepository $webhookRepository,
        DiscordService $discordService
    ) {
        $this->webhookRepository = $webhookRepository;
        $this->discordService = $discordService;
    }

    public function execute($id): WebhookResource
    {
        $cacheKey = 'webhook:message:'.md5($id);

        return Cache::tags(['webhook_message'])
            ->remember($cacheKey, 1, function () use ($id) {
                $webhook = $this->webhookRepository->findByHash($id);

                if (!$webhook) {
                    throw new ResourceNotFoundException();
                }

                $result = $this->processData(request()->all());

                $this->sendMessage($result, $webhook->my_webhook, $webhook->application);

                return WebhookResource::make($webhook);
            });
    }

    private function processData($request) : DiscordBag
    {
        return DiscordBag::fromRequest($request);
    }

    private function sendMessage(DiscordBag $discordBag, string $webhook, string $application) : void
    {
        $this->discordService
            ->title('Alert! - ' . $application . ' | ' . $discordBag->type)
            ->description([
                $discordBag->repository_full_name,
                $discordBag->display_name,
                $discordBag->branch_name,
                $discordBag->summary,
            ])
            ->footer('Time: ')
            ->success()
            ->timestamp(Carbon::now())
            ->send($webhook);
    }
}

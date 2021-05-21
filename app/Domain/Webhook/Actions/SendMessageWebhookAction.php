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

        $webhook = Cache::tags(['webhook_message'])
            ->remember($cacheKey, 3600, function () use ($id) {
                $webhook = $this->webhookRepository->findByHash($id);

                if (!$webhook) {
                    throw new ResourceNotFoundException();
                }

                return $webhook;
            });

        $result = $this->processData(request()->all());

        $this->sendMessage($result, $webhook->my_webhook, $webhook->application);

        return WebhookResource::make($webhook);
    }

    private function processData($request) : DiscordBag
    {
        return DiscordBag::fromRequest($request);
    }

    private function sendMessage(DiscordBag $discordBag, string $webhook, string $application) : void
    {
        $this->discordService
            ->title('Alert! - ' . $application . ' | ' . $discordBag->type)
            ->description($this->description($discordBag))
            ->footer('Time: ')
            ->success()
            ->timestamp(Carbon::now())
            ->send($webhook);
    }

    private function description(DiscordBag $discordBag) : array {

        if ($discordBag->type == 'push') {
            return [
                'Repository: ' . $discordBag->repository,
                'Actor: ' . $discordBag->actor,
                $discordBag->branch_name,
                $discordBag->summary,
            ];
        }

        if ($discordBag->type == 'pullrequest') {
            return [
                'Repository: ' .$discordBag->repository,
                'Actor: ' . $discordBag->actor,
                'PullRequest: '. $discordBag->source . ' => ' . $discordBag->destination,
                '',
                $discordBag->summary,
            ];
        }

        if ($discordBag->type == 'pullrequest' && $discordBag->approval != null) {
            return [
                'Repository: ' .$discordBag->repository,
                'Approved: ' . $discordBag->approval,
                'PullRequest: '. $discordBag->source . ' => ' . $discordBag->destination,
                '',
                $discordBag->summary,
            ];
        }

        if ($discordBag->type == 'commit_status') {
            return [
                'Repository: ' . $discordBag->repository,
                'Actor: ' . $discordBag->actor,
                'Status: '. $discordBag->state
            ];
        }
    }
}

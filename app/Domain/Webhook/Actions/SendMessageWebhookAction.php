<?php

namespace Domain\Webhook\Actions;

use App\Api\Resources\WebhookResource;
use App\Exceptions\ResourceNotFoundException;
use Carbon\Carbon;
use Domain\Webhook\Bags\DiscordBag;
use Domain\Webhook\Bags\SentryBag;
use Domain\Webhook\Models\Webhook;
use Domain\Webhook\Models\WebhookHistorie;
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

        if ($webhook->application == Webhook::BITBUCKET) {
            $bag = DiscordBag::fromRequest(request()->all());
        }

        if ($webhook->application == Webhook::SENTRY) {
            $bag = SentryBag::fromRequest(request()->all());
        }

        $this->saveHistories($bag, $webhook);

        $this->sendMessage($bag, $webhook->my_webhook, $webhook->application);

        return WebhookResource::make($webhook);
    }

    private function sendMessage($Bag, string $webhook, string $application) : void
    {
        if ($application == Webhook::BITBUCKET) {
            $this->discordService
                ->title('Alert! - ' . $application . ' | ' . $Bag->type)
                ->description($this->descriptionDiscord($Bag))
                ->footer('Time: ')
                ->success()
                ->timestamp(Carbon::now())
                ->send($webhook);
        }

        if ($application == Webhook::SENTRY) {

            $this->discordService
                ->title('Alert! - ' . $application . ' | ' . $Bag->type)
                ->description($this->descriptionSentry($Bag))
                ->footer('Time: ')
                ->success()
                ->timestamp(Carbon::now())
                ->send($webhook);
        }
    }

    private function descriptionDiscord(DiscordBag $discordBag) : array
    {

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
    private function descriptionSentry(SentryBag $bag) : array
    {
        return [
            'Repository: ' . $bag->type,
            'Actor: ' . $bag->actor,
            'Link: ' . $bag->url,
            'Description: ' . $bag->description,
            'Exception: ' . $bag->title_exception,
        ];
    }

    private function saveHistories($bag, Webhook $webhook): void
    {
        WebhookHistorie::create([
            'content' => $bag->jsonAttributes(),
            'webhook_id' => $webhook->id,
            'content_original' => (request()->expectsJson()) ? request()->all() : null
        ]);
    }
}

<?php

namespace Domain\User\Actions;

use Domain\User\Bags\MailUserBag;
use Mailgun\Mailgun;
use Spatie\QueueableAction\QueueableAction;

class SendUserMailAction
{
    use QueueableAction;

    public function execute(MailUserBag $mailUserBag)
    {
        if (env('MAILGUN_SECRET') && env('MAILGUN_ENDPOINT')) {

            $mgClient = Mailgun::create(
                env('MAILGUN_SECRET'), // Mailgun API Key
                env('MAILGUN_ENDPOINT'),
            );

            $domain = env('MAILGUN_DOMAIN');
            $params = [
                'subject' => $mailUserBag->subject,
                'to' => $mailUserBag->to,
                'from' => $mailUserBag->from,
                'template' => $mailUserBag->template,
                'v:username' => $mailUserBag->username,
                'v:company_name' => $mailUserBag->company_name,
            ];

            return $mgClient->messages()
                ->send($domain, $params)
                ->getId();
        }

        return false;
    }
}

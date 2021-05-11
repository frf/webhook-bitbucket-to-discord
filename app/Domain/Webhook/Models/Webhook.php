<?php

namespace Domain\Webhook\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Webhook
 * @package Domain\Webhook\Models
 * @property int id
 * @property int user_id
 * @property string webhook_hash
 */
class Webhook extends Model
{
    public const URL_WEBHOOK = 'https://webhook.app2u.co/v1/webhook-message/';

    protected $fillable = [
        'application',
        'content',
        'webhook_hash',
        'user_id',
    ];

    protected $hidden = [
    ];

    protected $visible = [
    ];

    protected $casts = [
    ];
}

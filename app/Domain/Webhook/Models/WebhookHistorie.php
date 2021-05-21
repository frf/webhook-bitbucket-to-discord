<?php

namespace Domain\Webhook\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Webhook
 * @package Domain\Webhook\Models
 * @property int id
 * @property int webhook_id
 * @property string content
 */
class WebhookHistorie extends Model
{
    protected $table = 'webhook_histories';

    protected $fillable = [
        'content',
        'webhook_id',
    ];

    protected $hidden = [
    ];

    protected $visible = [
    ];

    protected $casts = [
    ];
}

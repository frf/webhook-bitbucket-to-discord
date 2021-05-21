<?php

namespace Domain\Webhook\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Webhook
 * @package Domain\Webhook\Models
 * @property int id
 * @property int webhook_id
 * @property string content
 * @property string content_original
 */
class WebhookHistorie extends Model
{
    protected $table = 'webhook_histories';

    protected $fillable = [
        'content',
        'webhook_id',
        'content_original',
    ];

    protected $hidden = [
    ];

    protected $visible = [
    ];

    protected $casts = [
        'content_original' => 'array',
    ];
}

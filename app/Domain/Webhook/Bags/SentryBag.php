<?php

namespace Domain\Webhook\Bags;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Psy\Util\Json;

/**
 * Class UserBag
 * @package Domain\User\Bags
 * @property string actor
 * @property string repository
 * @property string type
 * @property string url
 * @property string description
 * @property string title_exception
 */
class SentryBag
{
    private array $attributes;

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function attributes(): array
    {
        return $this->attributes;
    }

    public function jsonAttributes()
    {
        return (is_array($this->attributes)) ? json_encode($this->attributes) : null;
    }

    public static function fromRequest($attributes)
    {
        $data = [];

        if (isset($attributes['data'])) {
            $data['type'] = $attributes['data']['triggered_rule'];
            $data['url'] = $attributes['data']['event']['issue_url'];

            if (isset($attributes['data']['event']['exception'])) {
                if (isset($attributes['data']['event']['exception']['values'])) {
                    if (isset($attributes['data']['event']['exception']['values'][0])) {
                        $data['title_exception'] = $attributes['data']['event']['exception']['values'][0]['type'];
                        $data['description'] = $attributes['data']['event']['exception']['values'][0]['value'];
                    }
                }
            }
        }

        if (isset($attributes['actor'])) {
            $data['actor'] = $attributes['actor']['name'];
        }

        return new self($data);
    }

    public function __get($name)
    {
        return $this->attributes[$name];
    }

    public function __set($name, $value)
    {
        return $this->attributes[$name] = $value;
    }
}

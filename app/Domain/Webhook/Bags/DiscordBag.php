<?php

namespace Domain\Webhook\Bags;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserBag
 * @package Domain\User\Bags
 * @property string display_name
 * @property string repository_full_name
 * @property string full_name
 * @property string type
 * @property string branch_name
 * @property string summary
 */
class DiscordBag
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

    public static function fromRequest($attributes)
    {
        $data = [];

        if (isset($attributes['push'])) {
            $data['type'] = key($attributes);
            $data['branch_name'] = (isset($attributes['push']['changes'][0]['old']['name'])) ?
                $attributes['push']['changes'][0]['old']['name'] : null;
            $data['summary'] = (isset($attributes['push']['changes'][0]['old']['target']['summary']['raw'])) ?
                $attributes['push']['changes'][0]['old']['target']['summary']['raw'] : null;
            $data['display_name'] = (isset($attributes['actor']['display_name'])) ?
                $attributes['actor']['display_name'] : null;
            $data['repository_full_name'] = (isset($attributes['repository']['full_name'])) ?
                $attributes['repository']['full_name'] : null;
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

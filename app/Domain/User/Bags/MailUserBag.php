<?php

namespace Domain\User\Bags;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class MailUserBag
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

    public static function fromRequest(UserBag $userBag, $subject, $template)
    {
        $data = [
            'subject' => $subject,
            'to' => $userBag->email,
            'from' => 'fabio@app2u.co',
            'template' => $template,
            'username' => $userBag->name,
            'company_name' => 'App2u',
        ];

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

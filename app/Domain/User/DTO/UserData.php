<?php

namespace Domain\User\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class UserData extends DataTransferObject {
    /** @var string */
    public $name;
    /** @var string */
    public $email;
    /** @var string */
    public $password;
}

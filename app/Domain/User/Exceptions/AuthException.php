<?php


namespace Domain\User\Exceptions;

use App\Exceptions\BaseException;
use Throwable;

class AuthException extends BaseException
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

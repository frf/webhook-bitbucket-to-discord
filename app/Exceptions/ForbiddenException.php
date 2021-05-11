<?php


namespace App\Exceptions;

class ForbiddenException extends BaseException
{
    protected $message = 'Forbidden';

    protected $code = 403;

    protected string $link = "http://linktodoc.com";

    protected int $internalCode = 01;

    protected string $instructions = "Write instructions about this error";
}

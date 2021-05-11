<?php


namespace App\Exceptions;

class BadRequestException extends BaseException
{
    protected $message = 'Bad Request';

    protected $code = 400;

    protected string $link = "http://linktodoc.com";

    protected int $internalCode = 01;

    protected string $instructions = "Write instructions about this error";
}

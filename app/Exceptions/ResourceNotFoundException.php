<?php


namespace App\Exceptions;

class ResourceNotFoundException extends BaseException
{
    protected $message = 'Resource not found';

    protected $code = 404;

    protected string $link = "http://linktodoc.com";

    protected int $internalCode = 01;

    protected string $instructions = "Write instructions about this error";
}

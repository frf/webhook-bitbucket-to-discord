<?php


namespace App\Exceptions;

/**
 * Class BaseException
 * @package App\Exceptions
 */
class BaseException extends \DomainException
{
    /**
     * @var string
     */
    protected string $link = 'http://linkdodoc.com';
    /**
     * @var int
     */
    protected int $internalCode = 0;
    /**
     * @var string
     */
    protected string $instructions = '';

    /**
     * @return int
     */
    public function getInternalCode(): int
    {
        return $this->internalCode;
    }

    /**
     * @param  int  $internalCode
     * @return BaseException
     */
    public function setInternalCode(int $internalCode): BaseException
    {
        $this->internalCode = $internalCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * @return int
     */
    public function getHttpCode(): int
    {
        return $this->httpCode;
    }

    /**
     * @param  int  $httpCode
     */
    public function setHttpCode(int $httpCode): void
    {
        $this->httpCode = $httpCode;
    }

    /**
     * @return string
     */
    public function getInstructions(): string
    {
        return $this->instructions;
    }

    /**
     * @param  string  $instructions
     */
    public function setInstructions(string $instructions): void
    {
        $this->instructions = $instructions;
    }
}

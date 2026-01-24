<?php

namespace App\Exception;

/**
 * @author Wilhelm Zwertvaegher
 */
class DomainException extends \Exception
{
    private ErrorCode $errorCode;

    public function __construct(ErrorCode $errorCode, string $message, int $code = 0, ?\Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->errorCode = $errorCode;
    }

    public function getErrorCode(): ErrorCode
    {
        return $this->errorCode;
    }
}

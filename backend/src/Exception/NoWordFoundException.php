<?php

namespace App\Exception;

/**
 * @author Wilhelm Zwertvaegher
 */
class NoWordFoundException extends DomainException
{
    public function __construct($code = 0, ?\Exception $previous = null)
    {
        parent::__construct(ErrorCode::ENTITY_NOT_FOUND, ErrorMessage::NO_WORD_FOUND, $code, $previous);
    }
}

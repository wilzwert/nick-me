<?php

namespace App\Exception;

/**
 * @author Wilhelm Zwertvaegher
 */
class WordNotFoundException extends DomainException
{
    public function __construct(int $code = 0, ?\Exception $previous = null)
    {
        parent::__construct(ErrorCode::ENTITY_NOT_FOUND, ErrorMessage::WORD_NOT_FOUND, $code, $previous);
    }
}

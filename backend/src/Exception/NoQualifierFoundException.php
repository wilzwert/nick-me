<?php

namespace App\Exception;

/**
 * @author Wilhelm Zwertvaegher
 */
class NoQualifierFoundException extends DomainException
{
    public function __construct(int $code = 0, ?\Exception $previous = null)
    {
        parent::__construct(ErrorCode::ENTITY_NOT_FOUND, ErrorMessage::NO_QUALIFIER_FOUND, $code, $previous);
    }
}

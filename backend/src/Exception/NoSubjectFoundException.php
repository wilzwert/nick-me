<?php

namespace App\Exception;

/**
 * @author Wilhelm Zwertvaegher
 */
class NoSubjectFoundException extends DomainException
{
    public function __construct(int $code = 0, ?\Exception $previous = null)
    {
        parent::__construct(ErrorCode::ENTITY_NOT_FOUND, ErrorMessage::NO_SUBJECT_FOUND, $code, $previous);
    }
}

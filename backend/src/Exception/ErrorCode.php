<?php

namespace App\Exception;


/**
 * @author Wilhelm Zwertvaegher
 */
enum ErrorCode: string
{
    case UNKNOWN_ERROR = 'Unknown error';
    case INVALID_EMAIL = 'Invalid email';
    case FIELD_CANNOT_BE_NULL = 'The field cannot be null';
    case FIELD_CANNOT_BE_EMPTY = 'The field cannot be empty';

    case ENTITY_ALREADY_EXISTS = 'Entity already exists';

    public function getMessage(): string
    {
        return $this->value;
    }

    public function getCode(): string
    {
        return $this->name;
    }
}

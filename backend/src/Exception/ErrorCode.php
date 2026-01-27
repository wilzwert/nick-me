<?php

namespace App\Exception;

/**
 * @author Wilhelm Zwertvaegher
 */
enum ErrorCode: string
{
    case INTERNAL = 'INTERNAL_ERROR';
    case ENTITY_EXISTS = 'ENTITY_EXISTS';
    case ENTITY_NOT_FOUND = 'ENTITY_NOT_FOUND';
    case VALIDATION_ERROR = 'VALIDATION_ERROR';
}

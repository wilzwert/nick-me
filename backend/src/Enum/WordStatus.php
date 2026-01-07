<?php

namespace App\Enum;

/**
 * @author Wilhelm Zwertvaegher
 */
enum WordStatus: string implements Enum
{
    case PENDING = 'PENDING';
    case REJECTED = 'REJECTED';
    case APPROVED = 'APPROVED';

    public static function fromString(string $value): Enum
    {
        return self::from(strtoupper($value));
    }
}

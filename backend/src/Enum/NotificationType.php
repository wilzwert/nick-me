<?php

namespace App\Enum;

/**
 * @author Wilhelm Zwertvaegher
 */
enum NotificationType: string implements Enum
{
    case CONTACT = 'contact';
    case REPORT = 'report';
    case SUGGESTION = 'suggestion';

    public static function fromString(string $value): Enum
    {
        return self::from(strtoupper($value));
    }
}

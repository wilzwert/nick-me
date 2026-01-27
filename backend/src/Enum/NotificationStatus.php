<?php

namespace App\Enum;

/**
 * @author Wilhelm Zwertvaegher
 */
enum NotificationStatus: string implements Enum
{
    case PENDING = 'pending';
    case HANDLED = 'handled';

    case READ = 'read';

    public static function fromString(string $value): Enum
    {
        return self::from(strtoupper($value));
    }
}

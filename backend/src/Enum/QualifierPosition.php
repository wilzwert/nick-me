<?php

namespace App\Enum;

/**
 * @author Wilhelm Zwertvaegher
 */
enum QualifierPosition: string implements Enum
{
    case BEFORE = 'before';

    case AFTER = 'after';

    public static function fromString(string $value): Enum
    {
        return self::from(strtolower($value));
    }
}

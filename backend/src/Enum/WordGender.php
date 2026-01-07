<?php

namespace App\Enum;

/**
 * @author Wilhelm Zwertvaegher
 */
enum WordGender: string implements Enum
{
    case M = 'M';
    case F = 'F';
    // by definition no defined gender, which means it can be used as both M and F
    case NEUTRAL = 'NEUTRAL';
    // gender may be automatically adapted with a locale based strategy
    case AUTO = 'AUTO';

    public static function fromString(string $value): WordGender
    {
        $normalized = strtoupper($value);

        return self::from($normalized);
    }
}

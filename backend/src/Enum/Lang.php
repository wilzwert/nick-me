<?php

namespace App\Enum;

/**
 * @author Wilhelm Zwertvaegher
 */
enum Lang: string implements Enum
{
    case FR = 'fr';
    case EN = 'en';

    public static function fromString(string $value): Enum
    {
        try {
            return self::from(strtolower($value));
        } catch (\Throwable $throwable) {
            return self::FR;
        }
    }
}

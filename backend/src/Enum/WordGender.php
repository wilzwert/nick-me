<?php

namespace App\Enum;

use App\Dto\Response\FullWordDto;

/**
 * @author Wilhelm Zwertvaegher
 */
enum WordGender: string
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
        return match ($normalized) {
            'M' => self::M,
            'F' => self::F,
            'NEUTRAL' => self::NEUTRAL,
            'AUTO' => self::AUTO,
            default => throw new \InvalidArgumentException("Unknown WordGender value '{$normalized}'"),
        };
    }

    /**
     * @return array<WordGender>
     */
    public static function all(): array
    {
        return [
            self::AUTO,
            self::M,
            self::F,
            self::NEUTRAL,
        ];
    }
}

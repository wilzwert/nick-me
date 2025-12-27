<?php

namespace App\Enum;

/**
 * @author Wilhelm Zwertvaegher
 */
enum OffenseLevel: int
{
    case LOW = 1;

    case MEDIUM = 5;

    case HIGH = 10;

    case VERY_HIGH = 15;

    case MAX = 20;

    public static function fromString(string $value): self
    {
        $normalized = strtoupper(preg_replace('/[^A-Za-z_]/', '_', $value));

        return match ($normalized) {
            'LOW' => self::LOW,
            'MEDIUM' => self::MEDIUM,
            'HIGH' => self::HIGH,
            'VERY_HIGH' => self::VERY_HIGH,
            'MAX' => self::MAX,
            default => throw new \InvalidArgumentException("Unknown offense level: {$normalized}"),
        };
    }

    /**
     * @return array<OffenseLevel>
     */
    public static function all(): array
    {
        return [
            self::LOW,
            self::MEDIUM,
            self::HIGH,
            self::VERY_HIGH,
            self::MAX
        ];
    }
}

<?php

namespace App\Enum;

/**
 * @author Wilhelm Zwertvaegher
 */
enum OffenseLevel: int implements Enum
{
    case LOW = 1;

    case MEDIUM = 5;

    case HIGH = 10;

    case VERY_HIGH = 15;

    case MAX = 20;

    public static function fromString(string $value): self
    {
        if (filter_var($value, FILTER_VALIDATE_INT)) {
            $v = (int) $value;
            try {
                return self::from($v);
            }
            catch (\Throwable $e) {
                return ($v >= self::MAX->value) ? self::MAX :
                    ($v >= self::VERY_HIGH->value ? self::VERY_HIGH :
                    ($v >= self::HIGH->value ? self::HIGH :
                    ($v >= self::MEDIUM->value ? self::MEDIUM :
                    self::LOW
                )));
            }
        }

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
}

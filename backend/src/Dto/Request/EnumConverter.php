<?php

namespace App\Dto\Request;

use App\Enum\Enum;

/**
 * @author Wilhelm Zwertvaegher
 */
class EnumConverter
{
    /**
     * @template T of Enum
     *
     * @param class-string<T> $className
     *
     * @return T
     *
     * @throws \InvalidArgumentException|\ValueError
     */
    public function convert(string $className, string $value): Enum
    {
        return $className::fromString($value);
    }
}

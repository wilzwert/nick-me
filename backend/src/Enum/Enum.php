<?php

namespace App\Enum;

/**
 * @author Wilhelm Zwertvaegher
 */
interface Enum
{
    public static function fromString(string $value): self;
}

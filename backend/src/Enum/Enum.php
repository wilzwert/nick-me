<?php

namespace App\Enum;

/**
 * @author Wilhelm Zwertvaegher
 */

interface Enum
{
    /**
     * @param string $value
     * @return self
     */
    public static function fromString(string $value): self;
}

<?php

namespace App\Dto\Request;

use App\Enum\Enum;
use Symfony\Component\Validator\Exception\ValidatorException;

/**
 * @author Wilhelm Zwertvaegher
 */
class EnumConverter
{
    /**
     * @template T of Enum
     * @param class-string<T> $className
     * @param string $value
     * @return T
     * @throws \InvalidArgumentException, \ValueError
     */
    public function convert(string $className, string $value): Enum {
        return $className::fromString($value);
    }
}

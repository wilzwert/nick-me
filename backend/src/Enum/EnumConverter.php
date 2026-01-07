<?php

namespace App\Enum;

use phpDocumentor\Reflection\Types\Mixed_;
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
     */
    public function convert(string $className, string $value): mixed {
        try {
            return $className::fromString($value);
        }
        catch (\Exception $e) {
            // TODO : throw validation error
            throw new ValidatorException($e->getMessage());
        }
    }
}

<?php

namespace App\Exception;

use App\Enum\Enum;

/**
 * @author Wilhelm Zwertvaegher
 */
class EnumConversionException extends \Exception
{
    /**
     * @template T of Enum
     * @param class-string<T> $className
     * @param string $fieldName
     * @param string $value
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct(private readonly string $className, private readonly string $fieldName, private readonly string $value, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct('Enum conversion failed: '.$className.'::'.$fieldName.': '.$value, $code, $previous);
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    public function getValue(): string
    {
        return $this->value;
    }


}

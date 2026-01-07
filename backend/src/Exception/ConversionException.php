<?php

namespace App\Exception;

/**
 * @author Wilhelm Zwertvaegher
 */
class ConversionException extends \Exception
{
    public function __construct(private readonly string $path, private readonly string $value, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct('Conversion failed: '.$path.': '.$value, $code, $previous);
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}

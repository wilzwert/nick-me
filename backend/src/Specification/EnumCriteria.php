<?php

namespace App\Specification;

/**
 * @template T
 * @author Wilhelm Zwertvaegher
 */
interface EnumCriteria
{
    /**
     * @return array<T>
     */
    public function getAllowedValues(): array;

    public function getField(): string;

    public function shouldApply(): bool;
}

<?php

namespace App\Specification;

/**
 * @template T
 * @author Wilhelm Zwertvaegher
 */
interface EnumCriterion extends Criterion
{
    /**
     * @return array<T>
     */
    public function getAllowedValues(): array;
}

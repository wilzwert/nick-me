<?php

namespace App\Specification\Criterion;

use App\Entity\GrammaticalRole;
use App\Entity\Word;

/**
 * @author Wilhelm Zwertvaegher
 */
class ValueCriterion implements Criterion
{
    /**
     * @param class-string<Word|GrammaticalRole> $targetEntity
     */
    public function __construct(
        private readonly string $targetEntity,
        private readonly string $field,
        private readonly mixed $value,
        private readonly ValueCriterionCheck $check,
    ) {
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function getCheck(): ValueCriterionCheck
    {
        return $this->check;
    }

    public function shouldApply(): bool
    {
        return true;
    }

    public function getTargetEntity(): string
    {
        return $this->targetEntity;
    }
}

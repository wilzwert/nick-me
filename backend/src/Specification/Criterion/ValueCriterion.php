<?php

namespace App\Specification\Criterion;

use App\Entity\GrammaticalRole;
use App\Entity\Word;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class ValueCriterion implements Criterion
{
    /**
     * @param class-string<Word|GrammaticalRole> $targetEntity
     * @param string $field
     * @param mixed $value
     * @param ValueCriterionCheck $check
     */
    public function __construct(
        private string $targetEntity,
        private string $field,
        private mixed $value,
        private ValueCriterionCheck $check
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

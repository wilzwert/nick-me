<?php

namespace App\Specification\Criterion;

use App\Entity\GrammaticalRole;
use App\Entity\Word;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class ValuesCriterion implements Criterion
{
    /**
     * @param class-string<Word|GrammaticalRole> $targetEntity
     * @param string $field
     * @param array $values
     * @param ValuesCriterionCheck $check
     */
    public function __construct(
        private string $targetEntity,
        private string $field,
        private array $values,
        private ValuesCriterionCheck $check
    ) {
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public function getCheck(): ValuesCriterionCheck
    {
        return $this->check;
    }

    public function shouldApply(): bool
    {
        return count($this->values) > 0;
    }

    public function getTargetEntity(): string
    {
        return $this->targetEntity;
    }
}

<?php

namespace App\Specification\Criterion;

use App\Entity\Word;
use App\Enum\OffenseLevel;

/**
 * @implements EnumCriterion<OffenseLevel>
 * @author Wilhelm Zwertvaegher
 */
readonly class OffenseLevelCriterion implements EnumCriterion
{

    public function __construct(
        private ?OffenseLevel $offenseLevel = null,
        private ?OffenseConstraintType $offenseConstraintType = OffenseConstraintType::LTE
    )
    {
    }


    /**
     * @return array<EnumCriterion>
     */
    public function getAllowedValues(): array
    {
        if (!$this->offenseLevel) {
            return OffenseLevel::all();
        }

        if ($this->offenseConstraintType == OffenseConstraintType::EXACT) {
            return [$this->offenseLevel];
        }

        // create result for LT constraint
        $result = match ($this->offenseLevel) {
            OffenseLevel::LOW => [],
            OffenseLevel::MEDIUM => [OffenseLevel::LOW],
            OffenseLevel::HIGH => [OffenseLevel::LOW, OffenseLevel::MEDIUM],
            OffenseLevel::VERY_HIGH => [OffenseLevel::LOW, OffenseLevel::MEDIUM, OffenseLevel::HIGH],
            OffenseLevel::MAX => [OffenseLevel::LOW, OffenseLevel::MEDIUM, OffenseLevel::HIGH, OffenseLevel::VERY_HIGH]
        };

        if ( $this->offenseConstraintType == OffenseConstraintType::LTE) {
            // add current OffenseLevel to allow equality
            $result[] = $this->offenseLevel;
        }
        return $result;
    }

    public function getField(): string
    {
        return 'offenseLevel';
    }

    public function shouldApply(): bool
    {
        return !empty($this->offenseLevel);
    }

    public function getTargetEntity(): string
    {
        return Word::class;
    }
}

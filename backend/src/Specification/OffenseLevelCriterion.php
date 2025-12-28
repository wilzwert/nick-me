<?php

namespace App\Specification;

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

        return match ($this->offenseLevel) {
            OffenseLevel::LOW => [OffenseLevel::LOW],
            OffenseLevel::MEDIUM => [OffenseLevel::LOW, OffenseLevel::MEDIUM],
            OffenseLevel::HIGH => [OffenseLevel::LOW, OffenseLevel::MEDIUM, OffenseLevel::HIGH],
            OffenseLevel::VERY_HIGH => [OffenseLevel::LOW, OffenseLevel::MEDIUM, OffenseLevel::HIGH, OffenseLevel::VERY_HIGH],
            OffenseLevel::MAX => [OffenseLevel::LOW, OffenseLevel::MEDIUM, OffenseLevel::HIGH, OffenseLevel::VERY_HIGH, OffenseLevel::MAX]
        };
    }

    public function getField(): string
    {
        return 'offenseLevel';
    }

    public function shouldApply(): bool
    {
        return !empty($this->offenseLevel);
    }
}

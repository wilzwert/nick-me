<?php

namespace App\Specification;

use App\Enum\WordGender;

/**
 * @implements EnumCriteria<WordGender>
 * @author Wilhelm Zwertvaegher
 */
readonly class GenderCriteria implements EnumCriteria
{

    public function __construct(
        private ?WordGender $gender = null,
        private ?GenderConstraintType $genderConstraintType = GenderConstraintType::RELAXED
    )
    {
    }

    /**
     * @return array<EnumCriteria>
     */
    public function getAllowedValues(): array
    {
        if (null === $this->gender) {
            return WordGender::all();
        }

        if($this->genderConstraintType == GenderConstraintType::EXACT) {
            return [$this->gender];
        }

        return match ($this->gender) {
            WordGender::AUTO, WordGender::NEUTRAL => [WordGender::all()],
            WordGender::F => [WordGender::AUTO, WordGender::NEUTRAL, WordGender::F],
            WordGender::M => [WordGender::AUTO, WordGender::NEUTRAL, WordGender::M],
        };
    }

    public function getField(): string
    {
        return 'word.gender';
    }

    public function shouldApply(): bool
    {
        return !empty($this->gender);
    }
}

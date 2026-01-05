<?php

namespace App\Specification\Criterion;

use App\Entity\Word;
use App\Enum\WordGender;

/**
 * @implements EnumCriterion<WordGender>
 * @author Wilhelm Zwertvaegher
 */
readonly class GenderCriterion implements EnumCriterion
{

    public function __construct(
        private ?WordGender $gender = null,
        private ?GenderConstraintType $genderConstraintType = GenderConstraintType::RELAXED
    )
    {
    }

    /**
     * @return array<WordGender>
     */
    public function getAllowedValues(): array
    {
        // TODO this should be removed because GenderCriterion does not apply when gender is not set
        if (null === $this->gender || $this->gender === WordGender::AUTO) {
            return WordGender::all();
        }

        // asking for NEUTRAL always requires a word to be NEUTRAL only
        // M, F or AUTO are compatible because they have a defined gender by definition
        if ($this->gender === WordGender::NEUTRAL) {
            return [WordGender::NEUTRAL];
        }

        if($this->genderConstraintType == GenderConstraintType::EXACT) {
            // asking for a non-neutral gender in EXACT mode implies compatibility with AUTO words that can be M or F
            return [$this->gender, WordGender::AUTO];
        }

        return match ($this->gender) {
            // asking for NEUTRAL should always return NEUTRAL words only, even in relaxed mode
            WordGender::NEUTRAL => [WordGender::NEUTRAL],
            // AUTO implies a word can be M, F, NEUTRAL or AUTO gendered
            WordGender::AUTO => WordGender::all(),
            // AUTO and NEUTRAL are compatible with F
            WordGender::F => [WordGender::F, WordGender::AUTO, WordGender::NEUTRAL],
            // AUTO and NEUTRAL are compatible with M
            WordGender::M => [WordGender::M, WordGender::AUTO, WordGender::NEUTRAL],
        };
    }

    public function getField(): string
    {
        return 'gender';
    }

    public function shouldApply(): bool
    {
        return !empty($this->gender);
    }

    public function getTargetEntity(): string
    {
        return Word::class;
    }
}

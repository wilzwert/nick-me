<?php

namespace App\Dto\Request;

use App\Enum\GrammaticalRoleType;
use App\Enum\OffenseLevel;
use App\Enum\WordGender;
use App\Exception\ValidationErrorMessage;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Wilhelm Zwertvaegher
 * Parameters for word retrieval (i.e. replace a word in a generated nick)
 *  - Lang
 *  - WordType
 *  - OffenseLevel (maybe null)
 *  - WordGender (maybe null)
 *  - exclusions : a list a word ids to exclude
 */
class RandomWordRequest implements Request
{
    /**
     * @param list<int> $exclusions
     */
    public function __construct(
        #[Assert\Type('integer', message: ValidationErrorMessage::INVALID_FIELD_VALUE)]
        private int $previousId,
        private GrammaticalRoleType $role,
        private WordGender $gender,
        private OffenseLevel $offenseLevel = OffenseLevel::MEDIUM,
        private array $exclusions = [],
    ) {
    }

    public function getPreviousId(): int
    {
        return $this->previousId;
    }

    public function getGrammaticalRoleType(): GrammaticalRoleType
    {
        return $this->role;
    }

    public function getGender(): ?WordGender
    {
        return $this->gender;
    }

    public function getOffenseLevel(): ?OffenseLevel
    {
        return $this->offenseLevel;
    }

    /**
     * @return list<int>
     */
    public function getExclusions(): array
    {
        return $this->exclusions;
    }
}

<?php

namespace App\Dto\Command;

use App\Entity\GrammaticalRole;
use App\Enum\GrammaticalRoleType;
use App\Enum\OffenseLevel;
use App\Enum\WordGender;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class GetWordCommand
{
    /**
     * @param list<int> $exclusions
     */
    public function __construct(
        private GrammaticalRoleType $role,
        private WordGender $gender,
        private OffenseLevel $offenseLevel = OffenseLevel::MEDIUM,
        private ?int $previousId = null,
        private ?GrammaticalRole $previous = null,
        private array $exclusions = [],
    ) {
        if (null === $previous  && null === $this->previousId) {
            throw new \InvalidArgumentException('Cannot get a word without a previous word or id');
        }
    }

    public function getRole(): GrammaticalRoleType
    {
        return $this->role;
    }

    public function getGender(): WordGender
    {
        return $this->gender;
    }

    public function getOffenseLevel(): OffenseLevel
    {
        return $this->offenseLevel;
    }

    public function getPreviousId(): ?int
    {
        return $this->previousId;
    }

    public function getPrevious(): ?GrammaticalRole
    {
        return $this->previous;
    }

    /**
     * @return list<int>
     */
    public function getExclusions(): array
    {
        return $this->exclusions;
    }
}

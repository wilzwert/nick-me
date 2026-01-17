<?php

namespace App\Dto\Request;

use App\Enum\GrammaticalRoleType;
use App\Enum\Lang;
use App\Enum\OffenseLevel;
use App\Enum\WordGender;

/**
 * @author Wilhelm Zwertvaegher
 * Parameters for random word / nick retrieval
 *  - Lang
 *  - OffenseLevel (maybe null)
 *  - WordGender (maybe null)
 *  - exclusions : a list a word ids to exclude
 */
readonly class RandomNickRequest implements Request
{
    /**
     * @param list<int> $exclusions
     */
    public function __construct(
        private Lang $lang = Lang::FR,
        private WordGender $gender = WordGender::AUTO,
        private ?OffenseLevel $offenseLevel = null,
        private ?int $previousId = null,
        private ?GrammaticalRoleType $replaceRole = null,
        private array $exclusions = [],
    ) {
        if (null !== $this->replaceRole && null === $this->previousId) {
            throw new \InvalidArgumentException('Unable to replace a word on an unknown nick');
        }
    }

    public function getLang(): Lang
    {
        return $this->lang;
    }

    public function getGender(): WordGender
    {
        return $this->gender;
    }

    public function getOffenseLevel(): ?OffenseLevel
    {
        return $this->offenseLevel;
    }

    public function getPreviousNickId(): ?int
    {
        return $this->previousId;
    }

    public function getReplaceRoleType(): ?GrammaticalRoleType
    {
        return $this->replaceRole;
    }

    /**
     * @return list<int>
     */
    public function getExclusions(): array
    {
        return $this->exclusions;
    }
}

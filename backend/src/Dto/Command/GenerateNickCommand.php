<?php

namespace App\Dto\Command;

use App\Enum\GrammaticalRoleType;
use App\Enum\Lang;
use App\Enum\OffenseLevel;
use App\Enum\WordGender;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class GenerateNickCommand
{
    /**
     * @param list<int> $exclusions
     */
    public function __construct(
        private Lang $lang,
        private ?WordGender $gender = null,
        private ?OffenseLevel $offenseLevel = null,
        private ?int $previousNickId = null,
        private ?GrammaticalRoleType $replaceRoleType = null,
        private array $exclusions = []
    ) {
    }

    public function getLang(): Lang
    {
        return $this->lang;
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

    public function getPreviousNickId(): ?int
    {
        return $this->previousNickId;
    }

    public function getReplaceRoleType(): ?GrammaticalRoleType
    {
        return $this->replaceRoleType;
    }

}

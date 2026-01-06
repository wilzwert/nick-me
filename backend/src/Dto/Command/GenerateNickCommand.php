<?php

namespace App\Dto\Command;

use App\Enum\Lang;
use App\Enum\OffenseLevel;
use App\Enum\WordGender;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class GenerateNickCommand
{
    /**
     * @param Lang $lang
     * @param WordGender|null $gender
     * @param OffenseLevel|null $offenseLevel
     * @param list<int> $exclusions
     */
    public function __construct(
        private Lang $lang,
        private ?WordGender $gender = null,
        private ?OffenseLevel $offenseLevel = null,
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



}

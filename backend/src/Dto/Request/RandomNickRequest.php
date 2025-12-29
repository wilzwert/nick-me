<?php

namespace App\Dto\Request;

use App\Enum\Lang;
use App\Enum\OffenseLevel;
use App\Enum\WordGender;
use App\Enum\GrammaticalRoleType;

/**
 * @author Wilhelm Zwertvaegher
 * Parameters for random word / nick retrieval
 *  - Lang
 *  - OffenseLevel (maybe null)
 *  - WordGender (maybe null)
 *  - exclusions : a list a word ids to exclude
 *
 */
readonly class RandomNickRequest
{
    private ?array $exclusions;

    public function __construct(
        private Lang $lang = Lang::FR,
        private WordGender $gender = WordGender::AUTO,
        private ?OffenseLevel $offenseLevel = null,
        string $exclusions = '',
    ) {
        $this->exclusions = !empty($exclusions) ? explode(',', $exclusions) : [];
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

    public function getExclusions(): array
    {
        return $this->exclusions;
    }

}

<?php

namespace App\Specification;

use App\Enum\Lang;
use App\Enum\OffenseLevel;
use App\Enum\WordGender;
use App\Enum\WordType;

/**
 * @author Wilhelm Zwertvaegher
 * Parameters for random word / nick retrieval
 *  - Lang
 *  - OffenseLevel (maybe null)
 *  - WordGender (maybe null)
 *  - exclusions : a list a word ids to exclude
 *
 */
readonly class WordCriteria
{
    /**
     * @param Lang $lang
     * @param WordType|null $wordType
     * @param array<EnumCriteria> $enumCriteria
     * @param array|null $exclusions
     */
    public function __construct(
        private Lang $lang = Lang::FR,
        private ?WordType $wordType = null,
        private array $enumCriteria = [],
        private ?array $exclusions
    ) {
    }

    public function getLang(): Lang
    {
        return $this->lang;
    }

    private function getWordType(): WordType
    {
        return $this->wordType;
    }

    /**
     * @return array<EnumCriteria>
     * */
    public function getEnumCriteria(): array
    {
        return $this->enumCriteria;
    }

    public function getExclusions(): array
    {
        return $this->exclusions;
    }
}

<?php

namespace App\Specification;

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
class WordCriteria
{
    /**
     * @param Lang $lang
     * @param GrammaticalRoleType|null $grammaticalRole
     * @param array<Criterion> $criteria
     * @param array|null $exclusions
     */
    public function __construct(
        private readonly Lang                 $lang = Lang::FR,
        private readonly ?GrammaticalRoleType $grammaticalRole = null,
        private array $criteria = [],
        private readonly array $exclusions = []
    ) {
    }

    public function getLang(): Lang
    {
        return $this->lang;
    }

    private function getGrammaticalRole(): GrammaticalRoleType
    {
        return $this->grammaticalRole;
    }

    /**
     * @return array<Criterion>
     * */
    public function getCriteria(): array
    {
        return $this->criteria;
    }

    public function getExclusions(): array
    {
        return $this->exclusions;
    }

    public function addCriterion(Criterion $criterion): void
    {
        $this->criteria[] = $criterion;
    }
}

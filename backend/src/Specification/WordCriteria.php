<?php

namespace App\Specification;

use App\Enum\Lang;
use App\Specification\Criterion\Criterion;

/**
 * @author Wilhelm Zwertvaegher
 * Parameters for random word / nick retrieval
 *  - Lang
 *  - OffenseLevel (maybe null)
 *  - WordGender (maybe null)
 *  - exclusions : a list a word ids to exclude
 */
class WordCriteria
{
    /**
     * @param array<Criterion> $criteria
     */
    public function __construct(
        private readonly Lang $lang = Lang::FR,
        private array $criteria = [],
    ) {
    }

    public function getLang(): Lang
    {
        return $this->lang;
    }

    /**
     * @return array<Criterion>
     * */
    public function getCriteria(): array
    {
        return $this->criteria;
    }

    public function addCriterion(Criterion $criterion): void
    {
        $this->criteria[] = $criterion;
    }
}

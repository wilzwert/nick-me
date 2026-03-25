<?php

namespace App\Specification;

use App\Specification\Criterion\Criterion;

/**
 * @author Wilhelm Zwertvaegher
 * Criteria collection
 */
class Criteria
{
    /**
     * @param array<Criterion> $criteria
     */
    public function __construct(private array $criteria = [])
    {
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

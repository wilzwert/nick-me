<?php

namespace App\Specification;

use App\Specification\Criterion\Criterion;

/**
 * @author Wilhelm Zwertvaegher
 */
interface CriterionConverter
{
    /**
     * @param array<Criterion> $criteria
     */
    public function applyAll(QueryBuilderInterface $qb, array $criteria, EntitiesAliases $aliases): void;

    public function apply(QueryBuilderInterface $qb, Criterion $criterion, int $criterionIndex, EntitiesAliases $aliases): void;
}

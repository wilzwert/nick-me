<?php

namespace App\Specification;

use App\Specification\Criterion\Criterion;
use Doctrine\ORM\QueryBuilder;

/**
 * @author Wilhelm Zwertvaegher
 */
interface CriterionConverter
{
    /**
     * @param array<Criterion> $criteria
     */
    public function applyAll(QueryBuilder $qb, array $criteria, EntitiesAliases $aliases): void;

    public function apply(QueryBuilder $qb, Criterion $criterion, int $criterionIndex, EntitiesAliases $aliases): void;
}

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
     * @param QueryBuilder $qb
     * @param array<Criterion> $criteria
     * @param EntitiesAliases $aliases
     * @return void
     */
    public function applyAll(QueryBuilder $qb, array $criteria, EntitiesAliases $aliases): void;

    /**
     * @param QueryBuilder $qb
     * @param Criterion $criterion
     * @param int $criterionIndex
     * @param EntitiesAliases $aliases
     * @return void
     */
    public function apply(QueryBuilder $qb, Criterion $criterion, int $criterionIndex, EntitiesAliases $aliases): void;
}

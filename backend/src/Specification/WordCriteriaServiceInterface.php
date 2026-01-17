<?php

namespace App\Specification;

use Doctrine\ORM\QueryBuilder;

/**
 * @author Wilhelm Zwertvaegher
 */
interface WordCriteriaServiceInterface
{
    public function applyWordCriteria(QueryBuilder $qb, WordCriteria $criteria, Sort $sort = Sort::RANDOM, ?EntitiesAliases $aliases = null): void;
}

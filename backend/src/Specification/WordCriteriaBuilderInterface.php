<?php

namespace App\Specification;

/**
 * @author Wilhelm Zwertvaegher
 */
interface WordCriteriaBuilderInterface
{
    public function applyWordCriteria(QueryBuilderInterface $qb, WordCriteria $criteria, Sort $sort = Sort::RANDOM, ?EntitiesAliases $aliases = null): void;
}

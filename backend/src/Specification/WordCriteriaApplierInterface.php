<?php

namespace App\Specification;

/**
 * @author Wilhelm Zwertvaegher
 */
interface WordCriteriaApplierInterface
{
    public function applyWordCriteria(QueryBuilderInterface $qb, Criteria $criteria, Sort $sort = Sort::RANDOM, ?EntitiesAliases $aliases = null): void;
}

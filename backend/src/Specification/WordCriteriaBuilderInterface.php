<?php

namespace App\Specification;


/**
 * @author Wilhelm Zwertvaegher
 */
interface WordCriteriaBuilderInterface
{
    /**
     * @param QueryBuilderInterface $qb
     * @param WordCriteria $criteria
     * @param Sort $sort
     * @param EntitiesAliases|null $aliases
     * @return void
     */
    public function applyWordCriteria(QueryBuilderInterface $qb, WordCriteria $criteria, Sort $sort = Sort::RANDOM, ?EntitiesAliases $aliases = null): void;
}

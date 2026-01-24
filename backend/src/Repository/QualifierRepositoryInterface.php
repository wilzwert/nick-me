<?php

namespace App\Repository;

use App\Entity\Qualifier;
use App\Specification\Sort;
use App\Specification\WordCriteria;

/**
 * @author Wilhelm Zwertvaegher
 */
interface QualifierRepositoryInterface
{
    public function findByWordId(int $wordId): ?Qualifier;

    /**
     * @param WordCriteria $criteria
     * @param Sort $sort
     * @return Qualifier|null
     */
    public function findOne(WordCriteria $criteria, Sort $sort = Sort::RANDOM): ?Qualifier;
}

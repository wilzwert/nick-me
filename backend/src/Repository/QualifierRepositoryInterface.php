<?php

namespace App\Repository;

use App\Entity\Qualifier;
use App\Specification\Criteria;
use App\Specification\Sort;

/**
 * @author Wilhelm Zwertvaegher
 */
interface QualifierRepositoryInterface
{
    public function findByWordId(int $wordId): ?Qualifier;

    public function findOne(Criteria $criteria, Sort $sort = Sort::RANDOM): ?Qualifier;
}

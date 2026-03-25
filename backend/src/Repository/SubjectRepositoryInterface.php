<?php

namespace App\Repository;

use App\Entity\Subject;
use App\Specification\Sort;
use App\Specification\Criteria;

/**
 * @author Wilhelm Zwertvaegher
 */
interface SubjectRepositoryInterface
{
    public function findByWordId(int $wordId): ?Subject;

    public function findOne(Criteria $criteria, Sort $sort = Sort::RANDOM): ?Subject;
}

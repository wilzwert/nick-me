<?php

namespace App\Repository;

use App\Entity\Subject;
use App\Specification\Sort;
use App\Specification\WordCriteria;

/**
 * @author Wilhelm Zwertvaegher
 */
interface SubjectRepositoryInterface
{
    public function findByWordId(int $wordId): ?Subject;

    public function findOne(WordCriteria $criteria, Sort $sort = Sort::RANDOM): ?Subject;

}

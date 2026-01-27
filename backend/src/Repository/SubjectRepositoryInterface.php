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

    /**
     * @param WordCriteria $criteria
     * @param Sort $sort
     * @return Subject|null
     */
    public function findOne(WordCriteria $criteria, Sort $sort = Sort::RANDOM): ?Subject;
}

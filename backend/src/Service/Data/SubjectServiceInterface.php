<?php

namespace App\Service\Data;

use App\Entity\Subject;
use App\Entity\Word;
use App\Specification\WordCriteria;

/**
 * @author Wilhelm Zwertvaegher
 */
interface SubjectServiceInterface
{
    /**
     * @return Subject
     */
    public function findOneRandomly(WordCriteria $criteria): Subject;

    public function createOrUpdate(Word $word): Subject;

    public function deleteIfExists(int $wordId): void;

    public function save(Subject $subject): void;
}

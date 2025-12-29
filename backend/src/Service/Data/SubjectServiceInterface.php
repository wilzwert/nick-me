<?php

namespace App\Service\Data;

use App\Entity\Subject;
use App\Entity\Word;
use App\Specification\WordCriteria;

/**
 * @extends GrammaticalRoleServiceInterface<Subject>
 * @author Wilhelm Zwertvaegher
 */
interface SubjectServiceInterface extends GrammaticalRoleServiceInterface
{
    /**
     * @return Subject
     */
    public function findOneRandomly(WordCriteria $criteria): Subject;

    public function createOrUpdate(Word $word): Subject;

    public function deleteIfExists(int $wordId): void;

    public function save(Subject $subject): void;
}

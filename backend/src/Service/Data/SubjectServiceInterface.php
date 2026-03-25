<?php

namespace App\Service\Data;

use App\Entity\Subject;
use App\Entity\Word;
use App\Specification\Criteria;

/**
 * @extends GrammaticalRoleServiceInterface<Subject>
 *
 * @author Wilhelm Zwertvaegher
 */
interface SubjectServiceInterface extends GrammaticalRoleServiceInterface
{
    public function findOneRandomly(Criteria $criteria): ?Subject;

    public function createOrUpdate(Word $word): Subject;

    public function deleteIfExists(int $wordId): void;

    public function save(Subject $subject): void;
}

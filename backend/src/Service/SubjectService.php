<?php

namespace App\Service;

use App\Entity\Subject;
use App\Entity\Word;

/**
 * @author Wilhelm Zwertvaegher
 */
class SubjectService implements SubjectServiceInterface
{

    public function createOrUpdate(Word $word): Subject
    {
        // TODO: Implement createOrUpdate() method.
        return new Subject($word);
    }

    public function deleteIfExists(Word $word): void
    {
        // TODO: Implement deleteIfExists() method.
    }
}

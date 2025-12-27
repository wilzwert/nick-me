<?php

namespace App\Service;

use App\Entity\Subject;
use App\Entity\Word;

/**
 * @author Wilhelm Zwertvaegher
 */
interface SubjectServiceInterface
{
    public function createOrUpdate(Word $word): Subject;

    public function deleteIfExists(Word $word): void;

}

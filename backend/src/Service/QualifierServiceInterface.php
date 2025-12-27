<?php

namespace App\Service;

use App\Entity\Qualifier;
use App\Entity\Word;

/**
 * @author Wilhelm Zwertvaegher
 */
interface QualifierServiceInterface
{
    public function createOrUpdate(Word $word): Qualifier;

    public function deleteIfExists(Word $word): void;
}


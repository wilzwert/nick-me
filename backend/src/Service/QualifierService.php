<?php

namespace App\Service;

use App\Entity\Qualifier;
use App\Entity\Word;
use App\Enum\QualifierPosition;

/**
 * @author Wilhelm Zwertvaegher
 */
class QualifierService implements QualifierServiceInterface
{
    public function createOrUpdate(Word $word): Qualifier
    {
        // TODO
        return new Qualifier($word, QualifierPosition::AFTER);
    }

    public function deleteIfExists(Word $word): void
    {
        // TODO: Implement deleteIfExists() method.
    }
}


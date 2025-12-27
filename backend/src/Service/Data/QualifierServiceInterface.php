<?php

namespace App\Service\Data;

use App\Entity\Qualifier;
use App\Entity\Word;
use App\Specification\MaintainQualifierSpec;
use App\Specification\WordCriteria;

/**
 * @author Wilhelm Zwertvaegher
 */
interface QualifierServiceInterface
{
    /**
     * @return Qualifier
     */
    public function findOneRandomly(WordCriteria $criteria): Qualifier;

    public function createOrUpdate(Word $word, MaintainQualifierSpec $command): Qualifier;

    public function deleteIfExists(int $wordId): void;

    public function save(Qualifier $qualifier): void;
}

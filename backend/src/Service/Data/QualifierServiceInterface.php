<?php

namespace App\Service\Data;

use App\Dto\Properties\MaintainQualifierProperties;
use App\Entity\Qualifier;
use App\Entity\Word;
use App\Specification\WordCriteria;

/**
 * @extends GrammaticalRoleServiceInterface<Qualifier>
 * @author Wilhelm Zwertvaegher
 */
interface QualifierServiceInterface extends GrammaticalRoleServiceInterface
{
    /**
     * @return Qualifier
     */
    public function findOneRandomly(WordCriteria $criteria): Qualifier;

    public function createOrUpdate(Word $word, MaintainQualifierProperties $command): Qualifier;

    public function deleteIfExists(int $wordId): void;

    public function save(Qualifier $qualifier): void;
}

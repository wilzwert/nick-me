<?php

namespace App\Service\Data;

use App\Dto\Properties\MaintainWordProperties;
use App\Entity\Word;

/**
 * @author Wilhelm Zwertvaegher
 */
interface WordServiceInterface
{
    public function createOrUpdate(MaintainWordProperties $spec): Word;

    public function save(Word $word): void;
}

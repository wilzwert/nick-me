<?php

namespace App\Service\Data;

use App\Entity\Word;
use App\Specification\MaintainWordSpec;

/**
 * @author Wilhelm Zwertvaegher
 */
interface WordServiceInterface
{
    public function createOrUpdate(MaintainWordSpec $spec): Word;

    public function save(Word $word): void;
}

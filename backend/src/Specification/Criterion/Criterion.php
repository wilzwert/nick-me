<?php

namespace App\Specification\Criterion;

use App\Entity\GrammaticalRole;
use App\Entity\Word;

/**
 * @author Wilhelm Zwertvaegher
 */
interface Criterion
{
    /***
     * @return class-string<Word|GrammaticalRole>
     */
    public function getTargetEntity(): string;

    public function getField(): string;

    public function shouldApply(): bool;
}

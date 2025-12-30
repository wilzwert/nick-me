<?php

namespace App\Entity;

/**
 * @author Wilhelm Zwertvaegher
 */
interface GrammaticalRole
{
    public function getWord(): Word;

    public function getUsageCount(): int;

    public function incrementUsageCount(): void;
}

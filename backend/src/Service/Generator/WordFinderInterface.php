<?php

namespace App\Service\Generator;

use App\Dto\Command\GetWordCommand;
use App\Entity\GrammaticalRole;

/**
 * @author Wilhelm Zwertvaegher
 */
interface WordFinderInterface
{
    public function findSimilar(GetWordCommand $command): ?GrammaticalRole;
}

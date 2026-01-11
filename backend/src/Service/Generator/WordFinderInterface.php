<?php

namespace App\Service\Generator;

use App\Dto\Command\GetWordCommand;
use App\Entity\GrammaticalRole;

/**
 * @author Wilhelm Zwertvaegher
 */
interface WordFinderInterface
{
    /**
     * @param GetWordCommand $command
     * @return GrammaticalRole
     */
    public function findSimilar(GetWordCommand $command): GrammaticalRole;
}

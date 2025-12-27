<?php

namespace App\UseCase;

use App\Dto\Command\MaintainWordCommand;

/**
 * @author Wilhelm Zwertvaegher
 */
interface MaintainWordInterface
{
    public function __invoke(MaintainWordCommand $maintainWordCommand): void;
}

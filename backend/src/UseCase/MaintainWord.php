<?php

namespace App\UseCase;

use App\Dto\Command\MaintainWordCommand;

/**
 * @author Wilhelm Zwertvaegher
 */
class MaintainWord implements MaintainWordInterface
{
    public function __invoke(MaintainWordCommand $maintainWordCommand): void
    {
        // TODO: Implement __invoke() method.
    }
}

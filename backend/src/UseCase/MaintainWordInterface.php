<?php

namespace App\UseCase;

use App\Dto\Command\MaintainWordCommand;
use App\Dto\Response\FullWordDto;

/**
 * @author Wilhelm Zwertvaegher
 */
interface MaintainWordInterface
{
    public function __invoke(MaintainWordCommand $maintainWordCommand): FullWordDto;
}

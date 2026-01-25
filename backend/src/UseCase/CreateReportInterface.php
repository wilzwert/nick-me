<?php

namespace App\UseCase;

use App\Dto\Command\CreateReportCommand;
use App\Entity\Report;

/**
 * @author Wilhelm Zwertvaegher
 */
interface CreateReportInterface
{
    public function __invoke(CreateReportCommand $command): Report;

}

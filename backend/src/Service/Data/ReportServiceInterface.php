<?php

namespace App\Service\Data;

use App\Dto\Command\CreateContactCommand;
use App\Dto\Command\CreateReportCommand;
use App\Entity\Contact;
use App\Entity\Report;

/**
 * @author Wilhelm Zwertvaegher
 */
interface ReportServiceInterface
{
    public function save(Report $report): void;

    public function create(CreateReportCommand $command): Report;
}

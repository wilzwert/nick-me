<?php

namespace App\Repository;

use App\Entity\Report;

/**
 * @author Wilhelm Zwertvaegher
 */
interface ReportRepositoryInterface
{
    public function getById(int $id): ?Report;
}

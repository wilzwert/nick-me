<?php

namespace App\Repository;

use App\Entity\Report;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Report>
 *
 * @author Wilhelm Zwertvaegher
 */
class ReportRepository extends ServiceEntityRepository implements ReportRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Report::class);
    }

    public function getById(int $id): ?Report
    {
        return parent::find($id);
    }

    public function getByNickIdAndSenderEmail(int $nickId, string $email): ?Report
    {
        return parent::findOneBy(['nick' => $nickId, 'senderEmail' => $email]);
    }
}

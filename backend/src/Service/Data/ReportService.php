<?php

namespace App\Service\Data;

use App\Dto\Command\CreateReportCommand;
use App\Entity\Report;
use App\Exception\NickNotFoundException;
use App\Repository\NickRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Clock\ClockInterface;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class ReportService implements ReportServiceInterface
{
    public function __construct(
        private NickRepositoryInterface $nickRepository,
        private EntityManagerInterface $entityManager,
        private ClockInterface $clock,
    ) {
    }

    public function save(Report $report): void
    {
        $this->entityManager->persist($report);
    }

    public function create(CreateReportCommand $command): Report
    {
        $nick = $this->nickRepository->getById($command->getNickId());
        if (null === $nick) {
            throw new NickNotFoundException();
        }

        $report = new Report(
            senderEmail: $command->getSenderEmail(),
            reason: $command->getReason(),
            nick: $nick,
            createdAt: $this->clock->now()
        );

        $this->save($report);

        return $report;
    }
}

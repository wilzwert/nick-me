<?php

namespace App\Application\UseCase;

use App\Dto\Command\CreateReportCommand;
use App\Entity\Report;
use App\Message\CommandBus;
use App\Message\SendNotificationCommand;
use App\Service\Data\NotificationServiceInterface;
use App\Service\Data\ReportServiceInterface;
use App\Service\Notification\Factory\NotificationPropsFactoryInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class CreateReport implements CreateReportInterface
{
    public function __construct(
        private ReportServiceInterface $reportService,
        private NotificationServiceInterface $notificationService,
        private EntityManagerInterface $entityManager,
        private NotificationPropsFactoryInterface $notificationFactory,
        private CommandBus $commandBus,
    ) {
    }

    public function __invoke(CreateReportCommand $command): Report
    {
        $report = $this->reportService->create($command);
        $notificationProps = $this->notificationFactory->create($report);
        $notification = $this->notificationService->create($notificationProps);
        $this->entityManager->flush();
        $this->commandBus->dispatch(new SendNotificationCommand($notification->getId()));

        return $report;
    }
}

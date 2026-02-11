<?php

namespace App\Tests\Integration\Application\UseCase;

use App\Application\UseCase\CreateReportInterface;
use App\Dto\Command\CreateReportCommand;
use App\Entity\Notification;
use App\Enum\NotificationStatus;
use App\Enum\NotificationType;
use App\Repository\NotificationRepositoryInterface;
use App\Repository\ReportRepositoryInterface;
use App\Tests\Support\AppTestData;
use PHPUnit\Framework\Attributes\Test;
use Psr\Clock\ClockInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Clock\MockClock;

/**
 * @author Wilhelm Zwertvaegher
 */
class ReportIT extends KernelTestCase
{
    #[Test]
    public function shouldCreateReport(): void
    {
        $now = new \DateTimeImmutable('2026-01-01 10:00:00');
        $senderEmail = 'sender@example.com';
        $reason = 'Report reason';
        $nickId = AppTestData::EXISTING_NICK_ID;

        self::bootKernel();
        $mockClock = new MockClock($now);
        self::getContainer()->set(ClockInterface::class, $mockClock);

        /** @var CreateReportInterface $useCase */
        $useCase = static::getContainer()->get(CreateReportInterface::class);
        $result = ($useCase)(new CreateReportCommand($senderEmail, $reason, $nickId));

        /** @var ReportRepositoryInterface $reportRepository */
        $reportRepository = static::getContainer()->get(ReportRepositoryInterface::class);
        $report = $reportRepository->getById($result->getId());
        self::assertNotNull($report);
        self::assertEquals($senderEmail, $report->getSenderEmail());
        self::assertEquals($reason, $report->getReason());
        self::assertEquals($now, $report->getCreatedAt());

        /** @var NotificationRepositoryInterface $notificationRepository */
        $notificationRepository = static::getContainer()->get(NotificationRepositoryInterface::class);
        // a pending Notification should have been created
        // to retrieve it, the better way is to load all notifications and find the one created now with sender@example.com
        self::assertCount(1,
            array_filter(
                $notificationRepository->findAll(),
                fn (Notification $notification) => NotificationType::REPORT === $notification->getType()
                    && $now == $notification->getCreatedAt()
                    && $now == $notification->getStatusUpdatedAt()
                    && 'admin@example.com' === $notification->getRecipientEmail()
                    && NotificationStatus::PENDING === $notification->getStatus()
            )
        );
    }
}

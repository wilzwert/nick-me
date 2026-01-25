<?php

namespace App\Tests\Integration\Application\UseCase;

use App\Application\UseCase\CreateContactInterface;
use App\Dto\Command\CreateContactCommand;
use App\Entity\Notification;
use App\Enum\NotificationStatus;
use App\Enum\NotificationType;
use App\Repository\ContactRepositoryInterface;
use App\Repository\NotificationRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use Psr\Clock\ClockInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Clock\MockClock;

/**
 * @author Wilhelm Zwertvaegher
 */
class ContactIT extends KernelTestCase
{
    #[Test]
    public function shouldCreateContact(): void
    {
        $now = new \DateTimeImmutable('2026-01-01 10:00:00');
        $senderEmail = 'sender@example.com';
        $contactContent = 'Contact content';

        self::bootKernel();
        $mockClock = new MockClock($now);
        self::getContainer()->set(ClockInterface::class, $mockClock);

        /** @var CreateContactInterface $useCase */
        $useCase = static::getContainer()->get(CreateContactInterface::class);
        $result = ($useCase)(new CreateContactCommand($senderEmail, $contactContent));

        /** @var ContactRepositoryInterface $contactRepository */
        $contactRepository = static::getContainer()->get(ContactRepositoryInterface::class);
        $contact = $contactRepository->getById($result->getId());
        self::assertNotNull($contact);
        self::assertEquals($senderEmail, $contact->getSenderEmail());
        self::assertEquals($contactContent, $contact->getContent());
        self::assertEquals($now, $contact->getCreatedAt());

        /** @var NotificationRepositoryInterface $notificationRepository */
        $notificationRepository = static::getContainer()->get(NotificationRepositoryInterface::class);
        // a pending Notification should have been created
        // to retrieve it, the better way is to load all notifications and find the one created now with sender@example.com
        self::assertCount(1,
            array_filter(
                $notificationRepository->findAll(),
                fn (Notification $notification) => NotificationType::CONTACT === $notification->getType()
                    && $now == $notification->getCreatedAt()
                    && $now == $notification->getStatusUpdatedAt()
                    && 'admin@example.com' === $notification->getRecipientEmail()
                    && NotificationStatus::PENDING === $notification->getStatus()
            )
        );
    }
}

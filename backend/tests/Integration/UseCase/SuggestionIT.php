<?php

namespace App\Tests\Integration\UseCase;

use App\Dto\Command\CreateContactCommand;
use App\Dto\Command\CreateSuggestionCommand;
use App\Entity\Notification;
use App\Enum\NotificationStatus;
use App\Enum\NotificationType;
use App\Exception\WordAlreadyExistsException;
use App\Repository\ContactRepositoryInterface;
use App\Repository\NotificationRepositoryInterface;
use App\Repository\SuggestionRepositoryInterface;
use App\UseCase\CreateContactInterface;
use App\UseCase\CreateSuggestionInterface;
use PHPUnit\Framework\Attributes\Test;
use Psr\Clock\ClockInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Clock\MockClock;

/**
 * @author Wilhelm Zwertvaegher
 */
class SuggestionIT extends KernelTestCase
{
    #[Test]
    public function shouldCreateSuggestion(): void
    {
        $now = new \DateTimeImmutable('2026-01-01 10:00:00');
        $senderEmail = 'sender@example.com';
        $label = 'SuggestedWord';

        self::bootKernel();
        $mockClock = new MockClock($now);
        self::getContainer()->set(ClockInterface::class, $mockClock);

        /** @var CreateSuggestionInterface $useCase */
        $useCase = static::getContainer()->get(CreateSuggestionInterface::class);
        $result = ($useCase)(new CreateSuggestionCommand($label, $senderEmail));

        /** @var SuggestionRepositoryInterface $suggestionRepository */
        $suggestionRepository = static::getContainer()->get(SuggestionRepositoryInterface::class);
        $suggestion = $suggestionRepository->getById($result->getId());
        self::assertNotNull($suggestion);
        self::assertEquals($label, $suggestion->getLabel());
        self::assertEquals($senderEmail, $suggestion->getCreatorEmail());
        self::assertEquals($now, $suggestion->getCreatedAt());

        /** @var NotificationRepositoryInterface $notificationRepository */
        $notificationRepository = static::getContainer()->get(NotificationRepositoryInterface::class);
        // a pending Notification should have been created
        // to retrieve it, the better way is to load all notifications and find the one created now with sender@example.com
        self::assertCount(1,
            array_filter(
                $notificationRepository->findAll(),
                fn (Notification $notification) => NotificationType::SUGGESTION === $notification->getType()
                    && $now == $notification->getCreatedAt()
                    && 'admin@example.com' === $notification->getRecipientEmail()
                    && NotificationStatus::PENDING === $notification->getStatus()
            )
        );
    }

    #[Test]
    public function shouldCreateSuggestionWithEmptyCreatorEmail(): void
    {
        $now = new \DateTimeImmutable('2026-01-01 10:00:00');
        $label = 'SuggestedWord';

        self::bootKernel();
        $mockClock = new MockClock($now);
        self::getContainer()->set(ClockInterface::class, $mockClock);

        /** @var CreateSuggestionInterface $useCase */
        $useCase = static::getContainer()->get(CreateSuggestionInterface::class);
        $result = ($useCase)(new CreateSuggestionCommand($label));

        /** @var SuggestionRepositoryInterface $suggestionRepository */
        $suggestionRepository = static::getContainer()->get(SuggestionRepositoryInterface::class);
        $suggestion = $suggestionRepository->getById($result->getId());
        self::assertNotNull($suggestion);
        self::assertEquals($label, $suggestion->getLabel());
        self::assertNull($suggestion->getCreatorEmail());
        self::assertEquals($now, $suggestion->getCreatedAt());

        /** @var NotificationRepositoryInterface $notificationRepository */
        $notificationRepository = static::getContainer()->get(NotificationRepositoryInterface::class);
        // a pending Notification should have been created
        // to retrieve it, the better way is to load all notifications and find the one created now with sender@example.com
        self::assertCount(1,
            array_filter(
                $notificationRepository->findAll(),
                fn (Notification $notification) => NotificationType::SUGGESTION === $notification->getType()
                    && $now == $notification->getCreatedAt()
                    && 'admin@example.com' === $notification->getRecipientEmail()
                    && NotificationStatus::PENDING === $notification->getStatus()
            )
        );
    }

    #[Test]
    public function whenWordExistsThenshouldThrowWordAlreadyExistsException(): void
    {
        self::expectException(WordAlreadyExistsException::class);

        /** @var CreateSuggestionInterface $useCase */
        $useCase = static::getContainer()->get(CreateSuggestionInterface::class);
        ($useCase)(new CreateSuggestionCommand('Nucléaire'));
    }
}

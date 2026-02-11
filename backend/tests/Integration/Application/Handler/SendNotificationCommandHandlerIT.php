<?php

namespace App\Tests\Integration\Application\Handler;

use App\Application\Handler\SendNotificationCommandHandler;
use App\Entity\NotificationLog;
use App\Enum\NotificationLogStatus;
use App\Exception\NotificationNotFoundException;
use App\Message\SendNotificationCommand;
use App\Repository\NotificationLogRepositoryInterface;
use App\Service\Notification\Dispatcher\NotificationDispatcher;
use App\Service\Notification\Dispatcher\NotificationDispatcherInterface;
use App\Service\Notification\Sender\EmailSender;
use App\Tests\Support\AppTestData;
use App\Tests\Support\Fake\FakeErrorSender;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @author Wilhelm Zwertvaegher
 */
class SendNotificationCommandHandlerIT extends KernelTestCase
{
    private SendNotificationCommandHandler $handler;

    public function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $container = self::getContainer();

        /* @var SendNotificationCommandHandler $handler */
        $this->handler = $container->get(SendNotificationCommandHandler::class);
    }

    /**
     * All Messages MUST be linked to at least an existing Identity.
     */
    #[Test]
    public function whenNotificationNotFoundThenShouldThrowNotificationNotFoundException(): void
    {
        // creating a random command for an unknown identity
        $command = new SendNotificationCommand(999);

        self::expectException(NotificationNotFoundException::class);
        ($this->handler)($command);

        self::assertEmailCount(0);

        // check no NotificationLog has been saved
        $container = self::getContainer();
        /** @var NotificationLogRepositoryInterface $repository */
        $repository = $container->get(NotificationLogRepositoryInterface::class);
        $notificationLogs = $repository->findByNotificationId($command->getNotificationId());
        self::assertCount(0, $notificationLogs);

        self::assertEmailCount(0);
        self::assertNotificationCount(0);
    }

    #[Test]
    public function shouldSendNotification(): void
    {
        $container = self::getContainer();

        $command = new SendNotificationCommand(AppTestData::EXISTING_PENDING_CONTACT_NOTIFICATION_ID);
        ($this->handler)($command);

        self::assertEmailCount(1);

        $email = $this->getMailerMessage();
        self::assertEmailHtmlBodyContains($email, 'Contact from the website');
        self::assertEmailAddressContains($email, 'To', 'test@example.com');

        // check a NotificationLogs have been saved
        /** @var NotificationLogRepositoryInterface $repository */
        $repository = $container->get(NotificationLogRepositoryInterface::class);
        self::assertCount(1, $repository->findByNotificationIdAndStatus($command->getNotificationId(), NotificationLogStatus::SENT));
    }

    #[Test]
    public function whenNotificationAlreadySentThenShouldNotResend(): void
    {
        // a command sent with a message already handled and sent by sms only (see fixtures)
        $command = new SendNotificationCommand(AppTestData::EXISTING_HANDLED_SUGGESTION_NOTIFICATION_ID);

        ($this->handler)($command);

        self::assertEmailCount(0);

        // check only one NotificationLog has been saved (for the email), but we know there already was one (see fixtures)
        /** @var NotificationLogRepositoryInterface $repository */
        $repository = self::getContainer()->get(NotificationLogRepositoryInterface::class);
        self::assertCount(0, $repository->findByNotificationId($command->getNotificationId()));
    }

    #[Test]
    public function shouldDispatchNotificationAndSaveNotificationLogs(): void
    {
        $container = self::getContainer();

        // get the real dispatcher to add a test sender
        /** @var NotificationDispatcher $dispatcher */
        $dispatcher = $container->get(NotificationDispatcherInterface::class);
        $errorSender = new FakeErrorSender();
        $dispatcher->addSender($errorSender);
        $emailSender = $container->get(EmailSender::class);

        $command = new SendNotificationCommand(AppTestData::EXISTING_PENDING_CONTACT_NOTIFICATION_ID);

        ($this->handler)($command);

        self::assertEmailCount(1);

        // check 2 NotificationLogs have been saved, including one in error
        /** @var NotificationLogRepositoryInterface $repository */
        $repository = $container->get(NotificationLogRepositoryInterface::class);
        $notificationLogs = $repository->findByNotificationId($command->getNotificationId());

        self::assertCount(2, $notificationLogs);
        self::assertTrue(
            array_any(
                $notificationLogs,
                fn (NotificationLog $notificationLog) => $notificationLog->getSender() === $errorSender->getName()
                    && NotificationLogStatus::ERROR === $notificationLog->getStatus()
            )
        );
        self::assertTrue(
            array_any(
                $notificationLogs,
                fn (NotificationLog $notificationLog) => $notificationLog->getSender() === $emailSender->getName()
                    && NotificationLogStatus::SENT === $notificationLog->getStatus()
            )
        );
    }
}

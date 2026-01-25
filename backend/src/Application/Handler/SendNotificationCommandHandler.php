<?php

namespace App\Application\Handler;

use App\Enum\NotificationLogStatus;
use App\Enum\NotificationStatus;
use App\Exception\NotificationNotFoundException;
use App\Message\SendNotificationCommand;
use App\Service\Data\NotificationLogServiceInterface;
use App\Service\Data\NotificationServiceInterface;
use App\Service\Notification\Dispatcher\NotificationDispatcherInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class SendNotificationCommandHandler implements SendNotificationCommandHandlerInterface
{
    public function __construct(
        private NotificationServiceInterface $notificationService,
        private NotificationDispatcherInterface $notificationDispatcher,
        private NotificationLogServiceInterface $notificationLogService,
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @throws NotificationNotFoundException
     */
    public function __invoke(SendNotificationCommand $command): void
    {
        // get notification to be sent
        $notification = $this->notificationService->getById($command->getNotificationId());
        if (null === $notification) {
            throw new NotificationNotFoundException();
        }

        if (NotificationStatus::PENDING !== $notification->getStatus()) {
            return;
        }

        // pass to dispatcher
        $results = $this->notificationDispatcher->dispatch($notification);
        // assume the dispatch is fully successful at first
        $isSuccess = true;

        // save logs for the notification
        foreach ($results as $result) {
            // it at least one dispatch result is not successful, then overall success must be false
            if (NotificationLogStatus::SENT !== $result->getStatus()) {
                $isSuccess = false;
            }

            $this->notificationLogService->createFromNotification(
                $notification,
                $result
            );
        }

        if ($isSuccess) {
            $this->notificationService->updateStatus($notification, NotificationStatus::HANDLED);
        }

        $this->entityManager->flush();
    }
}

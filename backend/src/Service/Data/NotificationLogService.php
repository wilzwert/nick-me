<?php

namespace App\Service\Data;

use App\Entity\Notification;
use App\Entity\NotificationLog;
use App\Service\Notification\Dispatcher\NotificationDispatchResult;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Clock\ClockInterface;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class NotificationLogService implements NotificationLogServiceInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ClockInterface $clock)
    {
    }

    public function save(NotificationLog $notificationLog): void
    {
        $this->entityManager->persist($notificationLog);
    }

    public function createFromNotification(Notification $notification, NotificationDispatchResult $result): NotificationLog
    {
        $notificationLog = new NotificationLog(
            notification: $notification,
            sender: $result->getSender(),
            status: $result->getStatus(),
            statusMessage: $result->getMessage(),
            createdAt: $this->clock->now()
        );
        $this->save($notificationLog);

        return $notificationLog;
    }
}

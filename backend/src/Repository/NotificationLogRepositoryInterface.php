<?php

namespace App\Repository;

use App\Entity\NotificationLog;
use App\Enum\NotificationLogStatus;

/**
 * @author Wilhelm Zwertvaegher
 */
interface NotificationLogRepositoryInterface
{
    /**
     * @param int $notificationId
     * @return array<NotificationLog>
     */
    public function findByNotificationId(int $notificationId): array;

    /**
     * @param int $notificationId
     * @param string $sender
     * @param NotificationLogStatus $status
     * @return array<NotificationLog>
     */
    public function findByNotificationIdAndSenderAndStatus(int $notificationId, string $sender, NotificationLogStatus $status): array;

    /**
     * @param int $notificationId
     * @param NotificationLogStatus $status
     * @return array<NotificationLog>
     */
    public function findByNotificationIdAndStatus(int $notificationId, NotificationLogStatus $status): array;


    public function hasSuccess(int $notificationId, string $sender): bool;
}

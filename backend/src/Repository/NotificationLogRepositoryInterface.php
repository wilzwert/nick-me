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
     * @return array<NotificationLog>
     */
    public function findByNotificationId(int $notificationId): array;

    /**
     * @return array<NotificationLog>
     */
    public function findByNotificationIdAndSenderAndStatus(int $notificationId, string $sender, NotificationLogStatus $status): array;

    /**
     * @return array<NotificationLog>
     */
    public function findByNotificationIdAndStatus(int $notificationId, NotificationLogStatus $status): array;

    public function hasSuccess(int $notificationId, string $sender): bool;
}

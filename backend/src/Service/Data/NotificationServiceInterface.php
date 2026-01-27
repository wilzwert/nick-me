<?php

namespace App\Service\Data;

use App\Entity\Notification;
use App\Enum\NotificationStatus;
use App\Service\Notification\Factory\NotificationProps;

/**
 * @author Wilhelm Zwertvaegher
 */
interface NotificationServiceInterface
{
    public function getById(int $id): ?Notification;

    public function save(Notification $notification): void;

    public function create(NotificationProps $props): Notification;

    public function updateStatus(Notification $notification, NotificationStatus $notificationStatus): void;
}

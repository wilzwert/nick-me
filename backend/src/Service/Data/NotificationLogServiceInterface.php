<?php

namespace App\Service\Data;

use App\Entity\Notification;
use App\Entity\NotificationLog;
use App\Service\Notification\Dispatcher\NotificationDispatchResult;

/**
 * @author Wilhelm Zwertvaegher
 */
interface NotificationLogServiceInterface
{
    public function save(NotificationLog $notificationLog): void;

    public function createFromNotification(Notification $notification, NotificationDispatchResult $result): NotificationLog;
}

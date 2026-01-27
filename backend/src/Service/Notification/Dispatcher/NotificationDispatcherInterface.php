<?php

namespace App\Service\Notification\Dispatcher;

use App\Entity\Notification;

/**
 * @author Wilhelm Zwertvaegher
 */
interface NotificationDispatcherInterface
{
    /**
     * @param Notification $notification
     * @return array<NotificationDispatchResult>
     */
    public function dispatch(Notification $notification): array;
}

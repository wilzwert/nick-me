<?php

namespace App\Service\Notification\Dispatcher;

use App\Entity\Notification;

/**
 * @author Wilhelm Zwertvaegher
 */
interface NotificationDispatcherInterface
{
    /**
     * @return array<NotificationDispatchResult>
     */
    public function dispatch(Notification $notification): array;
}

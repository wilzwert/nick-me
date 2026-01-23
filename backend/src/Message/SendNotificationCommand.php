<?php

namespace App\Message;

/**
 * @author Wilhelm Zwertvaegher
 */
class SendNotificationCommand extends Command
{
    public function __construct(private readonly int $notificationId)
    {
    }

    public function getNotificationId(): int
    {
        return $this->notificationId;
    }
}

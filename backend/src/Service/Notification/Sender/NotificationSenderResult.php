<?php

namespace App\Service\Notification\Sender;

use App\Enum\NotificationLogStatus;

/**
 * @author Wilhelm Zwertvaegher
 */
final readonly class NotificationSenderResult
{
    public function __construct(
        private NotificationLogStatus $status,
        private string $message,
    ) {
    }

    public function getStatus(): NotificationLogStatus
    {
        return $this->status;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}

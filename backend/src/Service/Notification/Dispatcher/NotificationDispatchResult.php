<?php

namespace App\Service\Notification\Dispatcher;

use App\Enum\NotificationLogStatus;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class NotificationDispatchResult
{
    public function __construct(
        private string $sender,
        private NotificationLogStatus $status,
        private string $message,
    ) {
    }

    public function getSender(): string
    {
        return $this->sender;
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

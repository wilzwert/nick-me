<?php

namespace App\Service\Notification\Factory;

use App\Enum\NotificationType;

/**
 * @author Wilhelm Zwertvaegher
 */
class NotificationProps
{
    public function __construct(
        private NotificationType $type,
        private string $recipientEmail,
        private string $subject,
        private string $content,
    ) {
    }

    public function getType(): NotificationType
    {
        return $this->type;
    }

    public function getRecipientEmail(): string
    {
        return $this->recipientEmail;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}

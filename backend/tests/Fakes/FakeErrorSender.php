<?php

namespace App\Tests\Fakes;

use App\Entity\Notification;
use App\Enum\NotificationLogStatus;
use App\Service\Notification\Sender\NotificationSenderInterface;
use App\Service\Notification\Sender\NotificationSenderResult;

/**
 * @author Wilhelm Zwertvaegher
 */
class FakeErrorSender implements NotificationSenderInterface
{

    public function supports(Notification $notification): bool
    {
        return true;
    }

    public function send(Notification $notification): NotificationSenderResult
    {
        return new NotificationSenderResult(NotificationLogStatus::ERROR, 'test_error');
    }

    public function getName(): string
    {
        return 'test_error_sender';
    }
}

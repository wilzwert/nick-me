<?php

namespace App\Service\Notification\Sender;

use App\Entity\Notification;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * @author Wilhelm Zwertvaegher
 */
#[AutoconfigureTag('app.notification_sender')]
interface NotificationSenderInterface
{
    public function supports(Notification $notification): bool;

    /**
     * @param Notification $notification
     * @return NotificationSenderResult
     */
    public function send(Notification $notification): NotificationSenderResult;

    public function getName(): string;
}

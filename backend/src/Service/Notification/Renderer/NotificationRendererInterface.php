<?php

namespace App\Service\Notification\Renderer;

use App\Entity\Notification;
use App\Service\Notification\Sender\NotificationSenderInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * @author Wilhelm Zwertvaegher
 */
#[AutoconfigureTag('app.notification_renderer')]
interface NotificationRendererInterface
{
    public function render(Notification $notification, NotificationSenderInterface $sender): string;
}

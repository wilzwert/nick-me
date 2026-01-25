<?php

namespace App\Service\Notification\Renderer;

use App\Entity\Notification;
use App\Service\Notification\Sender\NotificationSenderInterface;
use Twig\Environment;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class HtmlNotificationRenderer implements NotificationRendererInterface
{
    public function __construct(
        private Environment $twig,
    ) {
    }

    public function render(Notification $notification, NotificationSenderInterface $sender): string
    {
        return $this->twig->render(
            'notifications/'.$sender->getName().'/'.$notification->getType()->value.'.html.twig',
            ['content' => $notification->getContent(), 'date' => $notification->getCreatedAt()]
        );
    }
}

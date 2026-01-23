<?php

namespace App\Service\Notification;

use App\Entity\Contact;
use App\Enum\NotificationType;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class ContactNotificationPropsBuilder implements NotificationPropsBuilder
{
    public function __construct(
        #[Autowire('%recipient.admin%')]
        private string $adminEmail,
    ) {
    }

    public function getSupportedClass(): string
    {
        return Contact::class;
    }

    /**
     * @param object $source
     * @return NotificationProps
     */
    public function buildProps(object $source): NotificationProps
    {
        if (!is_a($source, Contact::class)) {
            throw new \InvalidArgumentException('$source must be a Contact');
        }

        return new NotificationProps(
            NotificationType::SUGGESTION,
            $this->adminEmail,
            'Site contact',
            sprintf(
                "A contact form has been submitted to you :from: %s\ncontent : %s",
                $source->getSenderEmail(),
                $source->getContent()
            ),
        );
    }
}

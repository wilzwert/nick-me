<?php

namespace App\Service\Notification\Factory;

use App\Entity\Contact;
use App\Enum\NotificationType;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * @author Wilhelm Zwertvaegher
 */
#[AsTaggedItem(index: Contact::class)]
readonly class ContactNotificationPropsBuilder implements NotificationPropsBuilder
{
    public function __construct(
        #[Autowire('%recipient.admin%')]
        private string $adminEmail,
    ) {
    }

    public function buildProps(object $source): NotificationProps
    {
        if (!is_a($source, Contact::class)) {
            throw new \InvalidArgumentException('$source must be a Contact');
        }

        return new NotificationProps(
            NotificationType::CONTACT,
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

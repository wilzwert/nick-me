<?php

namespace App\Service\Notification;

use App\Entity\Word;
use App\Enum\NotificationType;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class SuggestionNotificationPropsBuilder implements NotificationPropsBuilder
{
    public function __construct(
        #[Autowire('%recipient.admin%')]
        private string $adminEmail,
    ) {
    }

    public function getSupportedClass(): string
    {
        return Word::class;
    }

    /**
     * @param object $source
     * @return NotificationProps
     */
    public function buildProps(object $source): NotificationProps
    {
        if (!is_a($source, Word::class)) {
            throw new \InvalidArgumentException('$source must be a Word');
        }

        return new NotificationProps(
            NotificationType::SUGGESTION,
            $this->adminEmail,
            'Words suggestion',
            sprintf('A word has been suggested to you : %s', $source->getLabel()),
        );
    }
}

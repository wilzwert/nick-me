<?php

namespace App\Service\Notification\Factory;

use App\Entity\Suggestion;
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
        return Suggestion::class;
    }

    /**
     * @param object $source
     * @return NotificationProps
     */
    public function buildProps(object $source): NotificationProps
    {
        if (!is_a($source, Suggestion::class)) {
            throw new \InvalidArgumentException('$source must be a Word');
        }

        return new NotificationProps(
            NotificationType::SUGGESTION,
            $this->adminEmail,
            'Words suggestion',
            sprintf('A word has been suggested to you by %s : %s', $source->getCreatorEmail() ?? 'unspecified', $source->getLabel()),
        );
    }
}

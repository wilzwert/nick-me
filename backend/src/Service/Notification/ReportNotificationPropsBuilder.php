<?php

namespace App\Service\Notification;

use App\Entity\Report;
use App\Enum\NotificationType;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class ReportNotificationPropsBuilder implements NotificationPropsBuilder
{
    public function __construct(
        #[Autowire('%recipient.admin%')]
        private string $adminEmail,
    ) {
    }

    public function getSupportedClass(): string
    {
        return Report::class;
    }

    /**
     * @param object $source
     * @return NotificationProps
     */
    public function buildProps(object $source): NotificationProps
    {
        if (!is_a($source, Report::class)) {
            throw new \InvalidArgumentException('$source must be a Contact');
        }

        return new NotificationProps(
            NotificationType::REPORT,
            $this->adminEmail,
            'Nick report',
            sprintf(
                "A nick has been reported : %s (%d)\nBy: %s\nReason : %s",
                $source->getNick()->getLabel(),
                $source->getNick()->getId(),
                $source->getSenderEmail(),
                $source->getReason()
            ),
        );
    }
}

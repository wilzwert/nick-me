<?php

namespace App\Service\Data;

use App\Entity\Notification;
use App\Enum\NotificationStatus;
use App\Service\Notification\NotificationProps;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Clock\ClockInterface;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class NotificationService implements NotificationServiceInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ClockInterface $clock,
    ) {
    }

    public function save(Notification $notification): void
    {
        $this->entityManager->persist($notification);
    }

    public function create(NotificationProps $props): Notification
    {
        $message = new Notification(
            $props->getType(),
            $props->getRecipientEmail(),
            $props->getSubject(),
            $props->getContent(),
            NotificationStatus::PENDING,
            $now = $this->clock->now(),
            $now
        );

        $this->save($message);

        return $message;
    }
}

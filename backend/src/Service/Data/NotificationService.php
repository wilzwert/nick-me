<?php

namespace App\Service\Data;

use App\Entity\Notification;
use App\Enum\NotificationStatus;
use App\Repository\NotificationRepository;
use App\Service\Notification\Factory\NotificationProps;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Clock\ClockInterface;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class NotificationService implements NotificationServiceInterface
{
    public function __construct(
        private NotificationRepository $notificationRepository,
        private EntityManagerInterface $entityManager,
        private ClockInterface $clock,
    ) {
    }

    public function getById(int $id): ?Notification
    {
        return $this->notificationRepository->getById($id);
    }

    public function save(Notification $notification): void
    {
        $this->entityManager->persist($notification);
    }

    public function create(NotificationProps $props): Notification
    {
        $now = $this->clock->now();
        $message = new Notification(
            $props->getType(),
            $props->getRecipientEmail(),
            $props->getSubject(),
            $props->getContent(),
            NotificationStatus::PENDING,
            $now,
            $now
        );

        $this->save($message);

        return $message;
    }

    public function updateStatus(Notification $notification, NotificationStatus $notificationStatus): void
    {
        $notification->setStatus($notificationStatus, $this->clock->now());
        $this->save($notification);
    }
}

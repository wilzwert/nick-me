<?php

namespace App\Repository;

use App\Entity\NotificationLog;
use App\Enum\NotificationLogStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @author Wilhelm Zwertvaegher
 *
 * @extends ServiceEntityRepository<NotificationLog>
 */
class NotificationLogRepository extends ServiceEntityRepository implements NotificationLogRepositoryInterface
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, NotificationLog::class);
    }

    /**
     * @return array<NotificationLog>
     */
    public function findByNotificationId(int $notificationId): array
    {
        return parent::findBy(['notification' => $notificationId]);
    }

    /**
     * @return array<NotificationLog>
     */
    public function findByNotificationIdAndSenderAndStatus(int $notificationId, string $sender, NotificationLogStatus $status): array
    {
        return parent::findBy(['notification' => $notificationId, 'sender' => $sender, 'status' => $status]);
    }

    /**
     * @return array<NotificationLog>
     */
    public function findByNotificationIdAndStatus(int $notificationId, NotificationLogStatus $status): array
    {
        return parent::findBy(['notification' => $notificationId, 'status' => $status]);
    }

    public function hasSuccess(int $notificationId, string $sender): bool
    {
        return count($this->findByNotificationIdAndSenderAndStatus($notificationId, $sender, NotificationLogStatus::SENT)) > 0;
    }
}

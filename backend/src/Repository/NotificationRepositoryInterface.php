<?php

namespace App\Repository;

use App\Entity\Notification;

/**
 * @author Wilhelm Zwertvaegher
 */
interface NotificationRepositoryInterface
{
    public function getById(int $id): ?Notification;

    /**
     * @return array<Notification>
     */
    public function findAll(): array;
}

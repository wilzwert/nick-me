<?php

namespace App\Repository;

use App\Entity\Notification;

/**
 * @author Wilhelm Zwertvaegher
 */
interface NotificationRepositoryInterface
{
    public function getById(int $id): ?Notification;
}

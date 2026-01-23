<?php

namespace App\Service\Data;

use App\Entity\Notification;
use App\Service\Notification\NotificationProps;

/**
 * @author Wilhelm Zwertvaegher
 */
interface NotificationServiceInterface
{
    public function save(Notification $notification): void;

    public function create(NotificationProps $props): Notification;
}

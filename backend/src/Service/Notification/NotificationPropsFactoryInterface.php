<?php

namespace App\Service\Notification;

use App\Entity\Notification;

/**
 * @author Wilhelm Zwertvaegher
 */
interface NotificationPropsFactoryInterface
{
    public function create(object $source): NotificationProps;
}

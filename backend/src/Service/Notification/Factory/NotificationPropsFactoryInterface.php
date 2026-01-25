<?php

namespace App\Service\Notification\Factory;

/**
 * @author Wilhelm Zwertvaegher
 */
interface NotificationPropsFactoryInterface
{
    public function create(object $source): NotificationProps;
}

<?php

namespace App\Service\Notification\Factory;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * @author Wilhelm Zwertvaegher
 */
#[AutoconfigureTag('app.notification_props_builder')]
interface NotificationPropsBuilder
{
    /**
     * @return class-string
     */
    public function getSupportedClass(): string;

    public function buildProps(object $source): NotificationProps;
}

<?php

namespace App\Application\Handler;

use App\Message\SendNotificationCommand;

/**
 * @author Wilhelm Zwertvaegher
 */
interface SendNotificationCommandHandlerInterface
{
    public function __invoke(SendNotificationCommand $command): void;
}

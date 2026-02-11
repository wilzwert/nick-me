<?php

namespace App\MessageHandler;

use App\Application\Handler\SendNotificationCommandHandlerInterface;
use App\Message\SendNotificationCommand;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

/**
 * @author Wilhelm Zwertvaegher
 */
#[AsMessageHandler]
readonly class SendNotificationCommandHandler implements CommandHandler
{
    public function __construct(private SendNotificationCommandHandlerInterface $notificationCommandHandler)
    {
    }

    public function __invoke(SendNotificationCommand $command): void
    {
        ($this->notificationCommandHandler)($command);
    }
}

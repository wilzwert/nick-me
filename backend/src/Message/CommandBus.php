<?php

namespace App\Message;

use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Global bus for commands.
 *
 * @author Wilhelm Zwertvaegher
 */
readonly class CommandBus
{
    public function __construct(private MessageBusInterface $commandBus)
    {
    }

    public function dispatch(Command $command): void
    {
        $this->commandBus->dispatch($command);
    }
}

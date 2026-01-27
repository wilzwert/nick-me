<?php

namespace App\Message;

use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Global bus for messages.
 *
 * @author Wilhelm Zwertvaegher
 */
readonly class EventBus
{
    public function __construct(private MessageBusInterface $eventBus)
    {
    }

    public function dispatch(Command $command): void
    {
        $this->eventBus->dispatch($command);
    }
}

<?php

namespace App\Message;

use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Global bus for events.
 * Unused at the moment, but one use case could appear soon : a NickGeneratedEvent triggering a notification.
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

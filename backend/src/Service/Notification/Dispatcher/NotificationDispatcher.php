<?php

namespace App\Service\Notification\Dispatcher;

use App\Entity\Notification;
use App\Repository\NotificationLogRepositoryInterface;
use App\Service\Notification\Sender\NotificationSenderInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

/**
 * @author Wilhelm Zwertvaegher
 */
class NotificationDispatcher implements NotificationDispatcherInterface
{
    /**
     * @var array<NotificationSenderInterface>
     */
    private array $senders;

    /**
     * @param iterable<NotificationSenderInterface> $senders
     */
    public function __construct(
        #[AutowireIterator('app.notification_sender')]
        iterable $senders,
        private readonly NotificationLogRepositoryInterface $notificationLogRepository,
    ) {
        $this->senders = is_array($senders) ? $senders : iterator_to_array($senders);
    }

    public function addSender(NotificationSenderInterface $notificationSender): void
    {
        if (!array_any($this->senders, fn ($sender) => $sender->getName() === $notificationSender->getName())) {
            $this->senders[] = $notificationSender;
        }
    }

    /**
     * @return array<NotificationDispatchResult>
     */
    public function dispatch(Notification $notification): array
    {
        $results = [];

        foreach ($this->senders as $sender) {
            if ($sender->supports($notification)
                // avoid resending a notification which already succeeded
                // this may be useful in case a notification previously partially failed, which could result in a global retry
                && !$this->notificationLogRepository->hasSuccess($notification->getId(), $sender->getName())
            ) {
                $senderResult = $sender->send($notification);
                $results[] = new NotificationDispatchResult($sender->getName(), $senderResult->getStatus(), $senderResult->getMessage());
            }
        }

        return $results;
    }
}

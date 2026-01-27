<?php

namespace App\Entity;

use App\Enum\NotificationLogStatus;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Wilhelm Zwertvaegher
 */
#[ORM\Entity]
#[ORM\Table(name: 'notification_log')]
class NotificationLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Notification::class, cascade: ['persist'], inversedBy: 'notification_logs')]
    #[ORM\JoinColumn(nullable: false)]
    private Notification $notification;

    #[ORM\Column(type: 'string')]
    private string $sender;

    #[ORM\Column(type: 'string', enumType: NotificationLogStatus::class)]
    private NotificationLogStatus $status;

    #[ORM\Column(type: 'string')]
    private string $statusMessage;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    /**
     * @param Notification $notification
     * @param string $sender
     * @param NotificationLogStatus $status
     * @param string $statusMessage
     * @param \DateTimeImmutable $createdAt
     */
    public function __construct(Notification $notification, string $sender, NotificationLogStatus $status, string $statusMessage, \DateTimeImmutable $createdAt)
    {
        $this->notification = $notification;
        $this->sender = $sender;
        $this->status = $status;
        $this->statusMessage = $statusMessage;
        $this->createdAt = $createdAt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNotification(): Notification
    {
        return $this->notification;
    }

    public function getSender(): string
    {
        return $this->sender;
    }

    public function getStatus(): NotificationLogStatus
    {
        return $this->status;
    }

    public function getStatusMessage(): string
    {
        return $this->statusMessage;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}

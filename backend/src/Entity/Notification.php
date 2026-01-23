<?php

namespace App\Entity;

use App\Enum\NotificationStatus;
use App\Enum\NotificationType;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Wilhelm Zwertvaegher
 */
#[ORM\Entity]
#[ORM\Table(name: 'message')]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 10, enumType: NotificationType::class)]
    private NotificationType $type;

    #[ORM\Column(type: 'string', length: 100)]
    private ?string $recipientEmail;

    #[ORM\Column(type: 'text')]
    private string $subject;

    #[ORM\Column(type: 'text')]
    private string $content;

    #[ORM\Column(type: 'string', length: 10, enumType: NotificationStatus::class)]
    private NotificationStatus $status;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $statusUpdatedAt;

    public function __construct(
        NotificationType $type,
        string $recipientEmail,
        string $subject,
        string $content,
        NotificationStatus $status,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $statusUpdatedAt)
    {
        $this->type = $type;
        $this->recipientEmail = $recipientEmail;
        $this->subject = $subject;
        $this->content = $content;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->statusUpdatedAt = $statusUpdatedAt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): NotificationType
    {
        return $this->type;
    }

    public function getRecipientEmail(): ?string
    {
        return $this->recipientEmail;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getStatus(): NotificationStatus
    {
        return $this->status;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getStatusUpdatedAt(): \DateTimeImmutable
    {
        return $this->statusUpdatedAt;
    }
}

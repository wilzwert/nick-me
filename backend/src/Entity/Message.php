<?php

namespace App\Entity;

use App\Enum\MessageStatus;
use App\Enum\MessageType;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Wilhelm Zwertvaegher
 */
#[ORM\Entity]
#[ORM\Table(name: 'message')]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 10, enumType: MessageType::class)]
    private MessageType $type;

    #[ORM\ManyToOne(targetEntity: Word::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Word $word;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $senderEmail;

    #[ORM\Column(type: 'text')]
    private string $content;

    #[ORM\Column(type: 'string', length: 10, enumType: MessageStatus::class)]
    private MessageStatus $status;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $statusUpdatedAt;

    public function __construct(
        MessageType $type,
        ?Word $word,
        ?string $senderEmail,
        string $content,
        MessageStatus $status,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $statusUpdatedAt)
    {
        $this->type = $type;
        $this->word = $word;
        $this->senderEmail = $senderEmail;
        $this->content = $content;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->statusUpdatedAt = $statusUpdatedAt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): MessageType
    {
        return $this->type;
    }

    public function getWord(): ?Word
    {
        return $this->word;
    }

    public function getSenderEmail(): ?string
    {
        return $this->senderEmail;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getStatus(): MessageStatus
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

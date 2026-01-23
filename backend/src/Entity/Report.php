<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author Wilhelm Zwertvaegher
 */
#[ORM\Entity]
#[ORM\Table(name: 'report')]
class Report
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100)]
    private string $senderEmail;

    #[ORM\Column(type: 'text')]
    private string $reason;

    #[ORM\ManyToOne(targetEntity: Nick::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Nick $nick;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function __construct(string $senderEmail, string $reason, Nick $nick, \DateTimeImmutable $createdAt)
    {
        $this->senderEmail = $senderEmail;
        $this->reason = $reason;
        $this->nick = $nick;
        $this->createdAt = $createdAt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSenderEmail(): string
    {
        return $this->senderEmail;
    }

    public function getReason(): string
    {
        return $this->reason;
    }

    public function getNick(): Nick
    {
        return $this->nick;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}

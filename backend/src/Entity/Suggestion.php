<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author Wilhelm Zwertvaegher
 */
#[ORM\Entity]
#[ORM\Table(name: 'suggestion')]
class Suggestion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $creatorEmail;

    #[ORM\Column(type: 'string', length: 50)]
    private string $label;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function __construct(
        ?string $creatorEmail,
        string $label,
        \DateTimeImmutable $createdAt,
    ) {
        $this->creatorEmail = $creatorEmail;
        $this->label = $label;
        $this->createdAt = $createdAt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatorEmail(): ?string
    {
        return $this->creatorEmail;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}

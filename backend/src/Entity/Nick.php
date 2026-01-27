<?php

namespace App\Entity;

use App\Enum\OffenseLevel;
use App\Enum\WordGender;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Wilhelm Zwertvaegher
 */
#[ORM\Entity]
#[ORM\Table(name: 'nick')]
#[ORM\UniqueConstraint(name: 'uq_nick_properties', columns: ['subject_id', 'qualifier_id', 'target_gender'])]
class Nick
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 100)]
    private string $label;

    #[ORM\ManyToOne(targetEntity: Subject::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Subject $subject;

    #[ORM\ManyToOne(targetEntity: Qualifier::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Qualifier $qualifier;

    #[ORM\Column(type: 'string', length: 10, enumType: WordGender::class)]
    private WordGender $targetGender;

    #[ORM\Column(type: 'integer', length: 3, enumType: OffenseLevel::class)]
    private OffenseLevel $offenseLevel;

    #[ORM\Column(type: 'integer')]
    private int $usageCount = 0;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $lastUsedAt;

    public function __construct(
        string $label,
        Subject $subject,
        Qualifier $qualifier,
        WordGender $targetGender,
        OffenseLevel $offenseLevel,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $lastUsedAt,
    ) {
        $this->label = $label;
        $this->subject = $subject;
        $this->qualifier = $qualifier;
        $this->targetGender = $targetGender;
        $this->offenseLevel = $offenseLevel;
        $this->createdAt = $createdAt;
        $this->lastUsedAt = $lastUsedAt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getSubject(): Subject
    {
        return $this->subject;
    }

    public function getQualifier(): Qualifier
    {
        return $this->qualifier;
    }

    public function getTargetGender(): WordGender
    {
        return $this->targetGender;
    }

    public function getOffenseLevel(): OffenseLevel
    {
        return $this->offenseLevel;
    }

    public function incrementUsageCount(): void
    {
        ++$this->usageCount;
    }

    public function getUsageCount(): int
    {
        return $this->usageCount;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getLastUsedAt(): \DateTimeImmutable
    {
        return $this->lastUsedAt;
    }
}

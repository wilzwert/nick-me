<?php

namespace App\Entity;

use App\Enum\QualifierPosition;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Wilhelm Zwertvaegher
 */
#[ORM\Entity]
#[ORM\Table(name: 'qualifier')]
#[ORM\UniqueConstraint(name: 'uq_qualifier_word', columns: ['word_id'])]
class Qualifier implements GrammaticalRole
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Word::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Word $word;

    #[ORM\Column(type: 'string', enumType: QualifierPosition::class)]
    private QualifierPosition $position;

    #[ORM\Column(type: 'integer')]
    private int $usageCount = 0;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $lastUsedAt;

    public function __construct(Word $word, QualifierPosition $position)
    {
        $this->word = $word;
        $this->position = $position;
    }

    public function getWord(): Word
    {
        return $this->word;
    }

    public function getPosition(): QualifierPosition
    {
        return $this->position;
    }

    public function getUsageCount(): int
    {
        return $this->usageCount;
    }

    public function setPosition(QualifierPosition $position): Qualifier
    {
        $this->position = $position;

        return $this;
    }

    public function incrementUsageCount(\DateTimeImmutable $usedAt): void
    {
        ++$this->usageCount;
        $this->lastUsedAt = $usedAt;
    }

    public function getLastUsedAt(): ?\DateTimeImmutable
    {
        return $this->lastUsedAt;
    }
}

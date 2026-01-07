<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author Wilhelm Zwertvaegher
 */
#[ORM\Entity]
#[ORM\Table(name: 'subject')]
#[ORM\UniqueConstraint(name: 'uq_subject_word', columns: ['word_id'])]
class Subject implements GrammaticalRole
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Word::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Word $word;

    #[ORM\Column(type: 'integer')]
    private int $usageCount = 0;

    public function __construct(Word $word)
    {
        $this->word = $word;
    }

    public function getWord(): Word
    {
        return $this->word;
    }

    public function getUsageCount(): int
    {
        return $this->usageCount;
    }

    public function incrementUsageCount(): void
    {
        ++$this->usageCount;
    }
}

<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author Wilhelm Zwertvaegher
 */

#[ORM\Entity]
#[ORM\Table(name: 'subject')]
class Subject
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

    public function getWord(): Word { return $this->word; }
}

<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author Wilhelm Zwertvaegher
 */
#[ORM\Entity]
#[ORM\Table(name: 'nick')]
class Nick
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: "string")]
    private string $hash;

    private string $label;

    #[ORM\ManyToOne(targetEntity: Subject::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Subject $subject;

    #[ORM\ManyToOne(targetEntity: Qualifier::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Qualifier $qualifier;

    #[ORM\Column(type: 'integer')]
    private int $usageCount = 0;

    public function __construct(
       string $hash,
       string $label,
       Subject $subject,
       Qualifier $qualifier
    ) {
        $this->hash = $hash;
        $this->label = $label;
        $this->subject = $subject;
        $this->qualifier = $qualifier;
    }

    public function setHash($hash): void
    {
        $this->hash = $hash;
    }

    public function getSubject(): Subject
    {
        return $this->subject;
    }

    public function getQualifier(): Qualifier
    {
        return $this->qualifier;
    }



}

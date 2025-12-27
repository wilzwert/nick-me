<?php

namespace App\Entity;

use App\Enum\Lang;
use App\Enum\OffenseLevel;
use App\Enum\WordGender;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Wilhelm Zwertvaegher
 */
#[ORM\Entity]
#[ORM\Table(name: 'word')]
class Word
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    private string $slug;

    #[ORM\Column(type: 'string', length: 50)]
    private string $label;

    #[ORM\Column(type: 'string', length: 10, enumType: WordGender::class)]
    private WordGender $gender;

    #[ORM\Column(type: 'string', length: 10, enumType: Lang::class)]
    private Lang $lang;


    #[ORM\Column(type: 'string', length: 10, enumType: OffenseLevel::class)]
    private OffenseLevel $offenseLevel;


    public function __construct(string $slug, string $label, WordGender $gender, Lang $lang, OffenseLevel $offenseLevel)
    {
        $this->slug = $slug;
        $this->label = $label;
        $this->gender = $gender;
        $this->lang = $lang;
        $this->offenseLevel = $offenseLevel;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getGender(): WordGender
    {
        return $this->gender;
    }

    public function getLang(): Lang
    {
        return $this->lang;
    }

    public function getOffenseLevel(): OffenseLevel
    {
        return $this->offenseLevel;
    }
}

<?php

namespace App\Entity;

use App\Enum\Lang;
use App\Enum\OffenseLevel;
use App\Enum\WordGender;
use App\Enum\WordStatus;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Wilhelm Zwertvaegher
 */
#[ORM\Entity]
#[ORM\Table(name: 'word')]
#[ORM\UniqueConstraint(name: 'uq_word_slug_lang', columns: ['slug', 'lang'])]
class Word
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 50)]
    private string $slug;

    #[ORM\Column(type: 'string', length: 50)]
    private string $label;

    #[ORM\Column(type: 'string', length: 10, enumType: WordGender::class)]
    private WordGender $gender;

    #[ORM\Column(type: 'string', length: 10, enumType: Lang::class)]
    private Lang $lang;

    #[ORM\Column(type: 'integer', length: 3, enumType: OffenseLevel::class)]
    private OffenseLevel $offenseLevel;

    #[ORM\Column(type: 'string', length: 10, enumType: WordStatus::class)]
    private WordStatus $status;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $updatedAt;

    public function __construct(
        string $slug,
        string $label,
        WordGender $gender,
        Lang $lang,
        OffenseLevel $offenseLevel,
        WordStatus $status,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $updatedAt,
    ) {
        $this->slug = $slug;
        $this->label = $label;
        $this->gender = $gender;
        $this->lang = $lang;
        $this->offenseLevel = $offenseLevel;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    public function getGender(): WordGender
    {
        return $this->gender;
    }

    public function setGender(WordGender $gender): void
    {
        $this->gender = $gender;
    }

    public function getLang(): Lang
    {
        return $this->lang;
    }

    public function setLang(Lang $lang): void
    {
        $this->lang = $lang;
    }

    public function getOffenseLevel(): OffenseLevel
    {
        return $this->offenseLevel;
    }

    public function setOffenseLevel(OffenseLevel $offenseLevel): void
    {
        $this->offenseLevel = $offenseLevel;
    }

    public function getStatus(): WordStatus
    {
        return $this->status;
    }

    public function setStatus(WordStatus $status): void
    {
        $this->status = $status;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}

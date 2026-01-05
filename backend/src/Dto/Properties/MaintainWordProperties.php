<?php

namespace App\Dto\Properties;

use App\Enum\Lang;
use App\Enum\OffenseLevel;
use App\Enum\QualifierPosition;
use App\Enum\WordGender;
use App\Enum\WordStatus;

/**
 * DTO used to pass properties to a service to maintain a Word
 * @author Wilhelm Zwertvaegher
 */
readonly class MaintainWordProperties
{
    public function __construct(
        private string             $label,
        private WordGender         $gender,
        private Lang               $lang,
        private OffenseLevel       $offenseLevel,
        private WordStatus         $status,
        private bool               $asSubject = false,
        private bool               $asQualifier = false,
        private ?QualifierPosition $qualifierPosition = null,
        private ?int               $wordId = null
    ) {
    }

    public function getWordId(): ?int
    {
        return $this->wordId;
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

    public function getStatus(): WordStatus
    {
        return $this->status;
    }

    public function isAsSubject(): bool
    {
        return $this->asSubject;
    }

    public function isAsQualifier(): bool
    {
        return $this->asQualifier;
    }

    public function getQualifierPosition(): ?QualifierPosition
    {
        return $this->qualifierPosition;
    }
}

<?php

namespace App\Dto\Command;

use App\Enum\Lang;
use App\Enum\OffenseLevel;
use App\Enum\QualifierPosition;
use App\Enum\WordGender;
use App\Enum\WordStatus;
use App\Exception\ValidationErrorMessage;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @author Wilhelm Zwertvaegher
 */
class MaintainWordCommand
{
    public function __construct(
        #[Assert\NotBlank(message: ValidationErrorMessage::FIELD_CANNOT_BE_EMPTY)]
        #[Assert\Length(min: 2, minMessage: ValidationErrorMessage::FIELD_VALUE_TOO_SHORT)]
        private readonly string $label,
        private readonly WordGender $gender,
        private readonly Lang $lang,
        private readonly OffenseLevel $offenseLevel,
        private readonly WordStatus $status,
        private readonly bool $asSubject = false,
        private readonly bool $asQualifier = false,
        private readonly ?QualifierPosition $qualifierPosition = null,
        private ?int $wordId = null,
        private readonly bool $handleDeletion = true,
    ) {
    }

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context, mixed $payload): void
    {
        if ($this->asQualifier && empty($this->qualifierPosition)) {
            $context->buildViolation(ValidationErrorMessage::QUALIFIER_POSITION_CANNOT_BE_EMPTY)
                ->atPath('qualifierPosition')
                ->addViolation();
        }
    }

    public function setWordId(int $wordId): self
    {
        $this->wordId = $wordId;

        return $this;
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

    public function isHandleDeletion(): bool
    {
        return $this->handleDeletion;
    }

    public function getQualifierPosition(): ?QualifierPosition
    {
        return $this->qualifierPosition;
    }
}

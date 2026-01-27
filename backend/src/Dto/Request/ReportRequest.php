<?php

namespace App\Dto\Request;

use App\Exception\ValidationErrorMessage;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class ReportRequest
{
    public function __construct(
        #[Assert\Email(message: ValidationErrorMessage::INVALID_EMAIL)]
        #[Assert\NoSuspiciousCharacters]
        private string $senderEmail,
        #[Assert\NotBlank(
            message: ValidationErrorMessage::FIELD_CANNOT_BE_EMPTY,
        )]
        #[Assert\Regex(
            pattern: '/<[^>]+>/',
            message: ValidationErrorMessage::FIELD_CANNOT_CONTAIN_HTML,
            match: false
        )]
        private string $reason,
        #[Assert\NotBlank(message: ValidationErrorMessage::FIELD_CANNOT_BE_EMPTY)]
        private int $nickId,
    ) {
    }

    public function getSenderEmail(): string
    {
        return $this->senderEmail;
    }

    public function getReason(): string
    {
        return $this->reason;
    }

    public function getNickId(): int
    {
        return $this->nickId;
    }
}

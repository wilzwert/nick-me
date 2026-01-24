<?php

namespace App\Dto\Request;

use App\Exception\ValidationErrorMessage;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class ContactRequest
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
        private string $content,
    ) {
    }

    public function getSenderEmail(): string
    {
        return $this->senderEmail;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}

<?php

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class ContactRequest
{
    public function __construct(
        #[Assert\Email]
        #[Assert\NoSuspiciousCharacters]
        private string $senderEmail,
        #[Assert\NotBlank]
        #[Assert\Regex(
            pattern: '/<[^>]+>/',
            message: 'Le message ne doit pas contenir de HTML.',
            match: false
        )]
        private string $content)
    {
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

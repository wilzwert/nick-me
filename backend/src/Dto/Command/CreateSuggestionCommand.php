<?php

namespace App\Dto\Command;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class CreateSuggestionCommand
{
    public function __construct(private string $label, private ?string $senderEmail = null)
    {
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getSenderEmail(): ?string
    {
        return $this->senderEmail;
    }
}

<?php

namespace App\Dto\Command;

use App\Service\Data\NotificationServiceInterface;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class CreateContactCommand
{
    public function __construct(private string $senderEmail, private string $content)
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

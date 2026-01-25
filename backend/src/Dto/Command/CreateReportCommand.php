<?php

namespace App\Dto\Command;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class CreateReportCommand
{
    public function __construct(private string $senderEmail, private string $reason, private int $nickId)
    {
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

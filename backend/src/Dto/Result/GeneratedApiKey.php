<?php

namespace App\Dto\Result;

use App\Entity\ApiKey;

readonly class GeneratedApiKey
{
    public function __construct(private ApiKey $apiKey, private string $rawApiKey)
    {
    }
    public function getApiKey(): ApiKey
    {
        return $this->apiKey;
    }

    public function getRawApiKey(): string
    {
        return $this->rawApiKey;
    }
}

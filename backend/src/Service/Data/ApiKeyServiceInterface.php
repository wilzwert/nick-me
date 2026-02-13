<?php

namespace App\Service\Data;

use App\Dto\Result\GeneratedApiKey;
use App\Entity\ApiKey;

interface ApiKeyServiceInterface
{
    public function findKey(int $id): ?ApiKey;

    public function createKey(): GeneratedApiKey;

    public function findValidKey(string $receivedKey): ?ApiKey;
}

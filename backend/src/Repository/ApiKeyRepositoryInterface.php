<?php

namespace App\Repository;

use App\Entity\ApiKey;

interface ApiKeyRepositoryInterface
{
    public function findById(int $id): ?ApiKey;

    public function findByHash(string $hash): ?ApiKey;

}

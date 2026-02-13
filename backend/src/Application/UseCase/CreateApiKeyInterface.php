<?php

namespace App\Application\UseCase;

use App\Dto\Response\ApiKeyDto;
use App\Entity\ApiKey;

interface CreateApiKeyInterface
{
    public function __invoke(): ApiKeyDto;
}

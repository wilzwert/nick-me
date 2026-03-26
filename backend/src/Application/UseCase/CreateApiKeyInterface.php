<?php

namespace App\Application\UseCase;

use App\Dto\Response\ApiKeyDto;

interface CreateApiKeyInterface
{
    public function __invoke(): ApiKeyDto;
}

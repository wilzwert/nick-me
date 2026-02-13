<?php

namespace App\Application\UseCase;

use App\Dto\Response\ApiKeyDto;
use App\Service\Data\ApiKeyServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

class CreateApiKey implements CreateApiKeyInterface
{
    public function __construct(
        private readonly ApiKeyServiceInterface $apiKeyService,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(): ApiKeyDto
    {
        $created = $this->apiKeyService->createKey();

        $this->entityManager->flush();

        return new ApiKeyDto($created->getRawApiKey());
    }
}

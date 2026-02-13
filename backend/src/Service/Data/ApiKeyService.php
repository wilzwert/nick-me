<?php

namespace App\Service\Data;

use App\Dto\Command\CreateContactCommand;
use App\Dto\Result\GeneratedApiKey;
use App\Entity\ApiKey;
use App\Entity\Contact;
use App\Repository\ApiKeyRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Clock\ClockInterface;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class ApiKeyService implements ApiKeyServiceInterface
{
    public function __construct(
        private ApiKeyRepositoryInterface $repository,
        private ApiKeyGeneratorInterface $generator,
        private EntityManagerInterface $entityManager,
        private ClockInterface $clock,
    ) {
    }

    public function findKey(int $id): ?ApiKey
    {
        return $this->repository->findById($id);
    }

    private function hashKey(string $key): string
    {
        return hash('sha256', $key);
    }

    public function createKey(): GeneratedApiKey
    {
        $rawKey = $this->generator->generate();

        $apiKey = new ApiKey(
            $this->hashKey($rawKey),
            $this->clock->now()
        );

        $this->entityManager->persist($apiKey);

        return new GeneratedApiKey($apiKey, $rawKey);
    }

    public function findValidKey(string $receivedKey): ?ApiKey
    {
        $apiKey = $this->repository->findByHash($this->hashKey($receivedKey));
        if (!$apiKey) {
            return null;
        }

        if ($apiKey->getExpiresAt() && $apiKey->getExpiresAt() < $this->clock->now()) {
            return null;
        }

        return $apiKey;
    }
}

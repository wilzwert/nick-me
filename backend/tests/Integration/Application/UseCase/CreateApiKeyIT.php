<?php

namespace App\Tests\Integration\Application\UseCase;

use App\Application\UseCase\CreateApiKeyInterface;
use App\Repository\ApiKeyRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use Psr\Clock\ClockInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Clock\MockClock;

/**
 * @author Wilhelm Zwertvaegher
 */
class CreateApiKeyIT extends KernelTestCase
{
    #[Test]
    public function shouldCreateApiKey(): void
    {
        $now = new \DateTimeImmutable('2026-01-01 10:00:00');

        self::bootKernel();
        $mockClock = new MockClock($now);
        self::getContainer()->set(ClockInterface::class, $mockClock);

        /** @var CreateApiKeyInterface $useCase */
        $useCase = static::getContainer()->get(CreateApiKeyInterface::class);
        $result = ($useCase)();

        /** @var ApiKeyRepositoryInterface $apiKeyRepository */
        $apiKeyRepository = static::getContainer()->get(ApiKeyRepositoryInterface::class);
        $apiKey = $apiKeyRepository->findByHash(hash('sha256', $result->key));
        self::assertNotNull($apiKey);
        self::assertEquals($now, $apiKey->getCreatedAt());
    }

}

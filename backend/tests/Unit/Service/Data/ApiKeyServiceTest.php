<?php

namespace App\Tests\Unit\Service\Data;

use App\Entity\ApiKey;
use App\Repository\ApiKeyRepositoryInterface;
use App\Service\Data\ApiKeyGenerator;
use App\Service\Data\ApiKeyService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Clock\ClockInterface;
use Symfony\Component\Clock\MockClock;

/**
 * @author Wilhelm Zwertvaegher
 */
#[AllowMockObjectsWithoutExpectations]
final class ApiKeyServiceTest extends TestCase
{
    private ApiKeyRepositoryInterface $repositoryMock;
    private EntityManagerInterface $entityManagerMock;
    private ClockInterface $clockMock;
    private ApiKeyService $service;

    private \DateTimeImmutable $now;

    protected function setUp(): void
    {
        // Mocks
        $this->now = new \DateTimeImmutable();
        $this->repositoryMock = $this->createMock(ApiKeyRepositoryInterface::class);
        $this->entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $this->clockMock = new MockClock($this->now);

        // actual simple generator
        $generator = new ApiKeyGenerator();

        $this->service = new ApiKeyService(
            $this->repositoryMock,
            $generator,
            $this->entityManagerMock,
            $this->clockMock
        );
    }

    #[Test]
    public function findKeyShouldDelegateToRepository(): void
    {
        $apiKey = new ApiKey('dummyhash', new \DateTimeImmutable());
        $this->repositoryMock
            ->expects(self::once())
            ->method('findById')
            ->with(123)
            ->willReturn($apiKey);

        $result = $this->service->findKey(123);

        self::assertSame($apiKey, $result);
    }

    #[Test]
    public function shouldCreateKey(): void
    {
        $now = $this->now;
        // on s'attend à ce que persist soit appelé une fois
        $this->entityManagerMock
            ->expects($this->once())
            ->method('persist')
            ->with($this->callback(function (ApiKey $apiKey) {
                return !empty($apiKey->getHash())
                    && $apiKey->getCreatedAt() == $this->now
                ;
            }));

        $generated = $this->service->createKey();

        self::assertInstanceOf(ApiKey::class, $generated->getApiKey());
        self::assertEquals($now, $generated->getApiKey()->getCreatedAt());
        self::assertNull($generated->getApiKey()->getExpiresAt());
        self::assertIsString($generated->getRawApiKey());
        self::assertSame($generated->getApiKey()->getHash(), hash('sha256', $generated->getRawApiKey()));
    }

    #[Test]
    public function whenKeyNotFoundThenShouldReturnNull(): void
    {
        $this->repositoryMock
            ->expects(self::once())
            ->method('findByHash')
            ->willReturn(null);

        $result = $this->service->findValidKey('anykey');

        self::assertNull($result);
    }

    public function whenExpired_thenShouldReturnNull(): void
    {
        $expiredAt = $this->now->sub(new \DateInterval('PT1H'));
        $apiKey = new ApiKey('hash', new \DateTimeImmutable(), $expiredAt);

        $this->repositoryMock
            ->method('findByHash')
            ->willReturn($apiKey);

        $result = $this->service->findValidKey('somekey');

        self::assertNull($result);
    }

    #[Test]
    public function shouldFindValidKey(): void
    {
        $expiresAt = $this->now->add(new \DateInterval('PT1H'));
        $apiKey = new ApiKey('hash', new \DateTimeImmutable(), $expiresAt);

        $this->repositoryMock
            ->method('findByHash')
            ->willReturn($apiKey);

        $result = $this->service->findValidKey('somekey');

        self::assertSame($apiKey, $result);
    }
}

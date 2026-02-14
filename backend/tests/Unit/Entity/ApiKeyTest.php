<?php

namespace App\Tests\Unit\Entity;

use App\Entity\ApiKey;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestCase;

/**
 * @author Wilhelm Zwertvaegher
 */
class ApiKeyTest extends TestCase
{
    #[Test]
    public function testApiKey(): void
    {
        $now = new \DateTimeImmutable();
        $apiKey = new ApiKey('apiKeyHash', $now);
        self::assertEquals('apiKeyHash', $apiKey->getHash());
        self::assertEquals($now, $apiKey->getCreatedAt());
        self::assertNull($apiKey->getExpiresAt());

        $apiKey = new ApiKey('apiKeyHash', $now, $now);
        self::assertEquals('apiKeyHash', $apiKey->getHash());
        self::assertEquals($now, $apiKey->getCreatedAt());
        self::assertEquals($now, $apiKey->getExpiresAt());
    }

}

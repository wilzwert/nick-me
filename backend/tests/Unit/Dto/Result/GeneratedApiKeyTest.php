<?php

namespace App\Tests\Unit\Dto\Result;

use App\Dto\Result\GeneratedApiKey;
use App\Entity\ApiKey;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @author Wilhelm Zwertvaegher
 */
class GeneratedApiKeyTest extends TestCase
{
    #[Test]
    public function shouldBuildGeneratedApiKey(): void
    {
        $apiKey = new ApiKey('hash', new \DateTimeImmutable(), new \DateTimeImmutable());
        $generatedApiKey = new GeneratedApiKey($apiKey, 'rawApiKey');

        self::assertSame($apiKey, $generatedApiKey->getApiKey());
        self::assertSame('rawApiKey', $generatedApiKey->getRawApiKey());
    }

}

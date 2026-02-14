<?php

namespace App\Tests\Unit\Service\Data;

use App\Service\Data\ApiKeyGenerator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @author Wilhelm Zwertvaegher
 */
class ApiKeyGeneratorTest extends TestCase
{
    private ApiKeyGenerator $apiKeyGenerator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiKeyGenerator = new ApiKeyGenerator();
    }

    #[Test]
    public function shouldGenerateString(): void
    {
        self::assertEquals(16, strlen($this->apiKeyGenerator->generate(16)));
        self::assertEquals(64, strlen($this->apiKeyGenerator->generate(64)));
    }

    #[Test]
    public function whenLengthTooSmall_thenShouldThrowInvalidLengthException(): void
    {
        self::expectException(\InvalidArgumentException::class);
        $this->apiKeyGenerator->generate(14);
    }

    #[Test]
    public function whenLengthTooBig_thenShouldThrowInvalidLengthException(): void
    {
        self::expectException(\InvalidArgumentException::class);
        $this->apiKeyGenerator->generate(66);
    }

    #[Test]
    public function whenLengthNotEven_thenShouldThrowInvalidLengthException(): void
    {
        self::expectException(\InvalidArgumentException::class);
        $this->apiKeyGenerator->generate(31);
    }
}

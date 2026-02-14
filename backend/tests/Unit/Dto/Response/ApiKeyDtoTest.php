<?php

namespace App\Tests\Unit\Dto\Response;

use App\Dto\Response\ApiKeyDto;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @author Wilhelm Zwertvaegher
 */
class ApiKeyDtoTest extends TestCase
{
    #[Test]
    public function testApiKeyDto(): void
    {
        $dto = new ApiKeyDto('raw');
        self::assertEquals('raw', $dto->key);
    }

}

<?php

namespace App\Tests\Unit\Security;

use App\Security\ApiUser;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @author Wilhelm Zwertvaegher
 */
class ApiUserTest extends TestCase
{
    #[Test]
    public function shouldBuildApiUser(): void
    {
        $apiUser = new ApiUser('identifier', ['ROLE_CLIENT']);
        self::assertEquals('identifier', $apiUser->getUserIdentifier());
        self::assertEquals(['ROLE_CLIENT'], $apiUser->getRoles());
    }
}

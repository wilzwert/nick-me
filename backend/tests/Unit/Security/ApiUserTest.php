<?php

namespace App\Tests\Unit\Security;

use App\Security\ApiUser;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Wilhelm Zwertvaegher
 */
class ApiUserTest extends TestCase
{
    #[Test]
    public function shouldBuildApiUser(): void
    {
        $apiUser = new ApiUser('identifier', ['ROLE_TEST']);
        self::assertInstanceOf(UserInterface::class, $apiUser);
        self::assertEquals('identifier', $apiUser->getUserIdentifier());
        self::assertEquals(['ROLE_TEST'], $apiUser->getRoles());
    }
}

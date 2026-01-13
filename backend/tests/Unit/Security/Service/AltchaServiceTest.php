<?php

namespace App\Tests\Unit\Security\Service;

use AltchaOrg\Altcha\Altcha;
use AltchaOrg\Altcha\Challenge;
use App\Security\Service\AltchaService;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @author Wilhelm Zwertvaegher
 */
class AltchaServiceTest extends TestCase
{
    #[Test]
    public function shouldCreateChallenge(): void
    {
        $altcha = $this->createMock(Altcha::class);
        $challenge = $this->createStub(Challenge::class);
        $altcha->expects($this->once())->method('createChallenge')->willReturn($challenge);

        $service = new AltchaService($altcha);

        $result = $service->createChallenge();
        self::assertEquals($challenge, $result);
    }

    #[Test]
    public function shouldVerifySolution(): void
    {
        $altcha = $this->createMock(Altcha::class);
        $altcha->expects($this->once())->method('verifySolution')->willReturn(true);

        $service = new AltchaService($altcha);

        $result = $service->verifySolution('str');
        self::assertEquals(true, $result);
    }
}

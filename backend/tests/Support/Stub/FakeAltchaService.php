<?php

namespace App\Tests\Support\Stub;

use AltchaOrg\Altcha\Challenge;
use App\Security\Service\AltchaServiceInterface;
use App\Tests\Support\AltchaTestData;

/**
 * @author Wilhelm Zwertvaegher
 */
class FakeAltchaService implements AltchaServiceInterface
{
    public function createChallenge(): Challenge
    {
        return new Challenge(
            'test_algorithm',
            'test_challenge',
            maxNumber: 100,
            salt: 'test_salt',
            signature: 'test_signature',
        );
    }

    public function verifySolution(string $data): bool
    {
        return AltchaTestData::VALID_PAYLOAD == $data;
    }
}

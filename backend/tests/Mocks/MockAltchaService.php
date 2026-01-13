<?php

namespace App\Tests\Mocks;

use AltchaOrg\Altcha\Challenge;
use App\Security\Service\AltchaServiceInterface;
use App\Tests\Support\AltchaTestData;

/**
 * @author Wilhelm Zwertvaegher
 */
class MockAltchaService implements AltchaServiceInterface
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
        return $data == AltchaTestData::VALID_PAYLOAD;
    }
}

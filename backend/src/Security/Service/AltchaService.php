<?php

namespace App\Security\Service;

use AltchaOrg\Altcha\Altcha;
use AltchaOrg\Altcha\Challenge;
use AltchaOrg\Altcha\ChallengeOptions;

/**
 * @author Wilhelm Zwertvaegher
 */
class AltchaService implements AltchaServiceInterface
{

    public function __construct(private readonly Altcha $altcha)
    {
    }

    public function createChallenge(): Challenge
    {
        // Create a new challenge
        $options = new ChallengeOptions(
            maxNumber: 50000, // the maximum random number
            expires: new \DateTimeImmutable()->add(new \DateInterval('PT2M')),
        );

        return $this->altcha->createChallenge($options);
    }

    public function verifySolution(string $data): bool
    {
        return $this->altcha->verifySolution($data);
    }
}

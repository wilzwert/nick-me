<?php

namespace App\Security\Service;

use AltchaOrg\Altcha\Challenge;

/**
 * @author Wilhelm Zwertvaegher
 */
interface AltchaServiceInterface
{
    public function createChallenge(): Challenge;

    public function verifySolution(string $data): bool;
}

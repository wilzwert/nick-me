<?php

namespace App\Security\Service;

use AltchaOrg\Altcha\Altcha;
use AltchaOrg\Altcha\Challenge;
use AltchaOrg\Altcha\ChallengeOptions;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 *
 * ALTCHA is fully disabled when ALTCHA_ENABLED=false
 * This happens typically in e2e testing in CI/Staging
 * In that case, backend verification is bypassed and frontend does not request challenges.
 * @author Wilhelm Zwertvaegher
 *
 */
class AltchaService implements AltchaServiceInterface
{
    public function __construct(
        private readonly Altcha $altcha,
        #[Autowire('%altcha.token_expiry_seconds%')]
        private readonly int $altchaTokenExpirySeconds,
        #[Autowire('%altcha.enabled%')]
        private readonly bool $altchaEnabled,
    ) {
    }

    public function createChallenge(): Challenge
    {
        // Create a new challenge
        $options = new ChallengeOptions(
            maxNumber: 50000, // the maximum random number
            expires: new \DateTimeImmutable()->add(new \DateInterval(sprintf('PT%sS', $this->altchaTokenExpirySeconds))),
        );

        return $this->altcha->createChallenge($options);
    }

    public function verifySolution(string $data): bool
    {
        if (!$this->altchaEnabled) {
            return true;
        }

        return $this->altcha->verifySolution($data);
    }
}

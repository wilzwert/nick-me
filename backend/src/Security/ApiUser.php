<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @phpstan-type UserRole 'ROLE_FRONTEND'|'ROLE_CLIENT'|'ROLE_ADMIN'
 *
 * @author Wilhelm Zwertvaegher
 */
readonly class ApiUser implements UserInterface
{
    /**
     * @param array<UserRole> $roles
     */
    public function __construct(
        private string $identifier,
        private array $roles,
    ) {
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->identifier;
    }
}

<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Wilhelm Zwertvaegher
 */
class ApiUser implements UserInterface {

    public function __construct(
        private string $identifier,
        private array $roles
    ) {
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->identifier;
    }
}

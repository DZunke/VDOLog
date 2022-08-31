<?php

declare(strict_types=1);

namespace VDOLog\Web\Security;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use VDOLog\Core\Domain\User as DomainUser;

final class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public function __construct(public readonly DomainUser $domainUser)
    {
    }

    /** @return string[] */
    public function getRoles(): array
    {
        if ($this->domainUser->isAdmin()) {
            return ['ROLE_ADMIN'];
        }

        return ['ROLE_USER'];
    }

    public function getPassword(): string
    {
        return $this->domainUser->getPassword();
    }

    public function getSalt(): string
    {
        return '';
    }

    public function eraseCredentials(): void
    {
    }

    public function getUsername(): string
    {
        // Deprecated, so redirect to user identifier
        return $this->getUserIdentifier();
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->domainUser->getEmail();
    }
}

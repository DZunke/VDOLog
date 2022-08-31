<?php

declare(strict_types=1);

namespace VDOLog\Web\Security;

use InvalidArgumentException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use VDOLog\Core\Domain\User as DomainUser;
use VDOLog\Core\Domain\User\CurrentUserProvider;

use function assert;

final class CurrentDomainUserProvider implements CurrentUserProvider
{
    public function __construct(private readonly TokenStorageInterface $tokenStorage)
    {
    }

    public function hasCurrentUser(): bool
    {
        $token = $this->tokenStorage->getToken();
        if (! $token instanceof TokenInterface) {
            return false;
        }

        return $token->getUser() instanceof User;
    }

    public function getCurrentUser(): DomainUser
    {
        if (! $this->hasCurrentUser()) {
            throw new InvalidArgumentException('There is no currently logged in user');
        }

        $token = $this->tokenStorage->getToken();
        assert($token instanceof TokenInterface);

        $user = $token->getUser();
        assert($user instanceof User);

        return $user->domainUser;
    }
}

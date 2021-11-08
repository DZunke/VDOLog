<?php

declare(strict_types=1);

namespace VDOLog\Web\Security;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use VDOLog\Core\Domain\UserRepository;

class WebUserProvider implements UserProviderInterface
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (! $user instanceof User) {
            throw new UnsupportedUserException();
        }

        $domainUser = $this->userRepository->get($user->getDomainUser()->getId());

        return new User($domainUser);
    }

    public function supportsClass(string $class): bool
    {
        return $class === User::class;
    }

    public function loadUserByUsername(string $username): UserInterface
    {
        // Deprecatred, so redirect call
        return $this->loadUserByIdentifier($username);
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $domainUser = $this->userRepository->findByEmail($identifier);
        if ($domainUser === null) {
            throw new UserNotFoundException();
        }

        return new User($domainUser);
    }
}

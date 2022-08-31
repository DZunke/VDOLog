<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\User;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\PasswordHasher\Hasher\SodiumPasswordHasher;
use VDOLog\Core\Domain\User;
use VDOLog\Core\Domain\UserRepository;

final class CreateUserHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly SodiumPasswordHasher $passwordHasher,
        private readonly User\CurrentUserProvider $currentUserProvider,
    ) {
    }

    public function __invoke(CreateUser $message): void
    {
        $user = User::create($message->email, $this->passwordHasher->hash($message->plainTextPassword));
        $user->setDisplayName($message->displayName);

        if ($message->isAdmin()) {
            $user->markAdmin();
        }

        if ($this->currentUserProvider->hasCurrentUser()) {
            $user->setCreatedBy($this->currentUserProvider->getCurrentUser());
        }

        $this->userRepository->save($user);
    }
}

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
        private UserRepository $userRepository,
        private SodiumPasswordHasher $passwordHasher,
        private User\CurrentUserProvider $currentUserProvider
    ) {
    }

    public function __invoke(CreateUser $message): void
    {
        $user = User::create($message->getEMail(), $this->passwordHasher->hash($message->getPlainTextPassword()));

        if ($message->isAdmin()) {
            $user->markAdmin();
        }

        if ($this->currentUserProvider->hasCurrentUser()) {
            $user->setCreatedBy($this->currentUserProvider->getCurrentUser());
        }

        $this->userRepository->save($user);
    }
}

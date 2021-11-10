<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\User;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\PasswordHasher\Hasher\SodiumPasswordHasher;
use VDOLog\Core\Domain\UserRepository;

final class ChangePasswordHandler implements MessageHandlerInterface
{
    public function __construct(
        private UserRepository $userRepository,
        private SodiumPasswordHasher $passwordHasher
    ) {
    }

    public function __invoke(ChangePassword $message): void
    {
        $user = $this->userRepository->get($message->getId());
        $user->changeCredentials($this->passwordHasher->hash($message->getPlainPassword()));

        $this->userRepository->save($user);
    }
}

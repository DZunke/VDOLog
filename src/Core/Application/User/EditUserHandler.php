<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\User;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use VDOLog\Core\Domain\UserRepository;

final class EditUserHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

    public function __invoke(EditUser $message): void
    {
        $user = $this->userRepository->get($message->id);
        $user->setDisplayName($message->displayName);
        $user->changeEMail($message->email);

        if ($message->isAdmin === true) {
            $user->markAdmin();
        }

        $this->userRepository->save($user);
    }
}

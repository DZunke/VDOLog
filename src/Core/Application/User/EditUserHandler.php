<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\User;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use VDOLog\Core\Domain\UserRepository;

final class EditUserHandler implements MessageHandlerInterface
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }

    public function __invoke(EditUser $message): void
    {
        $user = $this->userRepository->get($message->getId());
        $user->setDisplayName($message->getDisplayName());
        $user->changeEMail($message->getEMail());

        if ($message->isAdmin()) {
            $user->markAdmin();
        }

        $this->userRepository->save($user);
    }
}

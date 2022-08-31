<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\User;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use VDOLog\Core\Domain\UserRepository;

final class UpdateProfileHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

    public function __invoke(UpdateProfile $message): void
    {
        $user = $this->userRepository->get($message->id);

        $user->setDisplayName($message->displayName);
        $message->enableNotifications ? $user->receiveNotifications() : $user->disableNotifications();

        $this->userRepository->save($user);
    }
}

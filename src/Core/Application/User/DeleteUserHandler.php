<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\User;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use VDOLog\Core\Domain\UserRepository;

final class DeleteUserHandler implements MessageHandlerInterface
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    public function __invoke(DeleteUser $message): void
    {
        $this->userRepository->delete($message->id);
    }
}

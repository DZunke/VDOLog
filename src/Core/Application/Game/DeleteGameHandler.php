<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Game;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use VDOLog\Core\Domain\GameRepository;

final class DeleteGameHandler implements MessageHandlerInterface
{
    public function __construct(private readonly GameRepository $gameRepository)
    {
    }

    public function __invoke(DeleteGame $message): void
    {
        $this->gameRepository->delete($message->id);
    }
}

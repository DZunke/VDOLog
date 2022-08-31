<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Game;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use VDOLog\Core\Domain\GameRepository;

final class UnlockGameHandler implements MessageHandlerInterface
{
    public function __construct(private readonly GameRepository $gameRepository)
    {
    }

    public function __invoke(UnlockGame $message): void
    {
        $game = $this->gameRepository->get($message->id);
        $game->setClosedAt(null);
        $this->gameRepository->save($game);
    }
}

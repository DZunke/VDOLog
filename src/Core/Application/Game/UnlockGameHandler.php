<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Game;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use VDOLog\Core\Domain\GameRepository;

final class UnlockGameHandler implements MessageHandlerInterface
{
    public function __construct(private GameRepository $gameRepository)
    {
    }

    public function __invoke(UnlockGame $message): void
    {
        $game = $this->gameRepository->get($message->getId());
        $game->setClosedAt(null);
        $this->gameRepository->save($game);
    }
}

<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Game;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use VDOLog\Core\Domain\GameRepository;

final class UnlockGameHandler implements MessageHandlerInterface
{
    private GameRepository $gameRepository;

    public function __construct(GameRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    public function __invoke(UnlockGame $message): void
    {
        $game = $this->gameRepository->get($message->getId());
        $game->setClosedAt(null);
        $this->gameRepository->save($game);
    }
}

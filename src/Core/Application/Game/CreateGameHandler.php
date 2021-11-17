<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Game;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use VDOLog\Core\Domain\Game;
use VDOLog\Core\Domain\GameRepository;
use VDOLog\Core\Domain\User\CurrentUserProvider;

final class CreateGameHandler implements MessageHandlerInterface
{
    public function __construct(
        private GameRepository $gameRepository,
        private CurrentUserProvider $currentUserProvider
    ) {
    }

    public function __invoke(CreateGame $message): void
    {
        $game = Game::create($message->getName());
        $game->setTimeFrame($message->getTimeFrame());

        if ($message->getLocation() !== null) {
            $game->setLocation($message->getLocation());
        }

        if ($this->currentUserProvider->hasCurrentUser()) {
            $game->setCreatedBy($this->currentUserProvider->getCurrentUser());
        }

        $this->gameRepository->save($game);
    }
}

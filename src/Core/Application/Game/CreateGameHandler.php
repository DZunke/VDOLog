<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Game;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use VDOLog\Core\Domain\Game;
use VDOLog\Core\Domain\GameRepository;

final class CreateGameHandler implements MessageHandlerInterface
{
    private GameRepository $gameRepository;

    public function __construct(GameRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    public function __invoke(CreateGame $message): void
    {
        $game = Game::create($message->getName());
        $this->gameRepository->save($game);
    }
}

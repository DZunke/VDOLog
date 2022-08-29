<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Game;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use VDOLog\Core\Domain\GameRepository;

final class EditGameHandler implements MessageHandlerInterface
{
    public function __construct(private GameRepository $gameRepository)
    {
    }

    public function __invoke(EditGame $message): void
    {
        $game = $this->gameRepository->get($message->getId());
        $game->setName($message->getName());
        $game->setTimeFrame($message->getTimeFrame());

        $this->gameRepository->save($game);
    }
}

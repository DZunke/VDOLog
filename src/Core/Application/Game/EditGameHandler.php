<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Game;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use VDOLog\Core\Domain\GameRepository;

final class EditGameHandler implements MessageHandlerInterface
{
    public function __construct(private readonly GameRepository $gameRepository)
    {
    }

    public function __invoke(EditGame $message): void
    {
        $game = $this->gameRepository->get($message->id);
        $game->setName($message->name);
        $game->setTimeFrame($message->timeFrame);

        $this->gameRepository->save($game);
    }
}

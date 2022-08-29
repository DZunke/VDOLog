<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Game;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use VDOLog\Core\Domain\GameRepository;

final class DeleteReminderHandler implements MessageHandlerInterface
{
    public function __construct(private GameRepository $gameRepository)
    {
    }

    public function __invoke(DeleteReminder $message): void
    {
        $game = $message->reminder->getGame();
        $game->removeReminder($message->reminder);

        $this->gameRepository->save($game);
    }
}

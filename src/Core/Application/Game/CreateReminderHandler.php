<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Game;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use VDOLog\Core\Domain\Game;
use VDOLog\Core\Domain\GameRepository;

final class CreateReminderHandler implements MessageHandlerInterface
{
    public function __construct(private GameRepository $gameRepository)
    {
    }

    public function __invoke(CreateReminder $message): void
    {
        $game = $message->getGame();

        $reminder = Game\Reminder::create(
            $game,
            $message->getTitle(),
            $message->getMessage(),
            $message->getRemindAt(),
        );

        $game->addReminder($reminder);

        $this->gameRepository->save($game);
    }
}

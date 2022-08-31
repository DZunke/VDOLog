<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Game;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use VDOLog\Core\Domain\Game;
use VDOLog\Core\Domain\GameRepository;

final class CreateReminderHandler implements MessageHandlerInterface
{
    public function __construct(private readonly GameRepository $gameRepository)
    {
    }

    public function __invoke(CreateReminder $message): void
    {
        $game = $message->game;

        $reminder = Game\Reminder::create(
            $game,
            $message->title,
            $message->message,
            $message->remindAt,
        );

        $game->addReminder($reminder);

        $this->gameRepository->save($game);
    }
}

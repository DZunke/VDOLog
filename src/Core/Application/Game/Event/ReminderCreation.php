<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Game\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use VDOLog\Core\Domain\Game\Event\GameCreated;
use VDOLog\Core\Domain\Game\Reminder;
use VDOLog\Core\Domain\Game\TimeFrame;
use VDOLog\Core\Domain\GameRepository;

final class ReminderCreation implements EventSubscriberInterface
{
    private GameRepository $gameRepository;

    public function __construct(GameRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    /** @inheritDoc */
    public static function getSubscribedEvents()
    {
        return [
            GameCreated::class => ['createSpectatorEntryReminders'],
        ];
    }

    public function createSpectatorEntryReminders(GameCreated $event): void
    {
        $game = $event->getGame();
        $game->addReminder(Reminder::create(
            $game,
            'Zuschauereinlass',
            'Der Einlass sollte begonnen werden.',
            $game->getTimeFrame()->getOption(TimeFrame::OPT_SPECTATOR_ENTRY)
        ));

        $this->gameRepository->save($game);
    }
}

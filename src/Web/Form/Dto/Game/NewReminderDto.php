<?php

declare(strict_types=1);

namespace VDOLog\Web\Form\Dto\Game;

use VDOLog\Core\Application\Game\CreateReminder;
use VDOLog\Core\Domain\Game;

class NewReminderDto
{
    private Game $game;

    public string $title    = '';
    public string $message  = '';
    public string $remindAt = '';

    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    public function toCommand(): CreateReminder
    {
        return new CreateReminder($this->game, $this->title, $this->message, $this->remindAt);
    }
}

<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Game;

use VDOLog\Core\Domain\Game;
use VDOLog\Core\Helper\Assertion;

class CreateReminder
{
    public function __construct(
        private Game $game,
        private string $title,
        private string $message,
        private string $remindAt,
    ) {
        Assertion::notBlank($title);
        Assertion::notBlank($message);
        Assertion::relativeDateTimeString($remindAt);
    }

    public function getGame(): Game
    {
        return $this->game;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getRemindAt(): string
    {
        return $this->remindAt;
    }
}

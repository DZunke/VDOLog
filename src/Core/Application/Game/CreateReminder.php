<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Game;

use VDOLog\Core\Domain\Game;
use VDOLog\Core\Helper\Assertion;

class CreateReminder
{
    public function __construct(
        public readonly Game $game,
        public readonly string $title,
        public readonly string $message,
        public readonly string $remindAt,
    ) {
        Assertion::notBlank($title);
        Assertion::notBlank($message);
        Assertion::relativeDateTimeString($remindAt);
    }
}

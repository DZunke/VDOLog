<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Game;

use VDOLog\Core\Domain\Game\Reminder;

final class DeleteReminder
{
    public function __construct(
        public Reminder $reminder
    ) {
    }
}

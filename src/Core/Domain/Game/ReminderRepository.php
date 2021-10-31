<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain\Game;

use DateTimeImmutable;

interface ReminderRepository
{
    /** @return iterable<Reminder> */
    public function findUnsentRemindersSince(DateTimeImmutable $lastCheck): iterable;
}

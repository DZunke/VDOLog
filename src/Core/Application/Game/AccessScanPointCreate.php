<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Game;

use DateTimeImmutable;

class AccessScanPointCreate
{
    public function __construct(
        public readonly string $gameId,
        public readonly DateTimeImmutable $time,
        public readonly int $entrances = 0,
        public readonly int $exits = 0,
        public readonly string|null $accessScannerId = null,
    ) {
    }
}

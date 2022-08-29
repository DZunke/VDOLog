<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Game;

use DateTimeImmutable;

class AccessScanPointCreate
{
    public function __construct(
        private string $gameId,
        private DateTimeImmutable $time,
        private int $entrances = 0,
        private int $exits = 0,
        private string|null $accessScannerId = null,
    ) {
    }

    public function getGameId(): string
    {
        return $this->gameId;
    }

    public function getTime(): DateTimeImmutable
    {
        return $this->time;
    }

    public function getEntrances(): int
    {
        return $this->entrances;
    }

    public function getExits(): int
    {
        return $this->exits;
    }

    public function getAccessScannerId(): string|null
    {
        return $this->accessScannerId;
    }
}

<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Game\Model\AccessScanPointCsvParser;

use DateTimeImmutable;

class AccessScanPoint
{
    private DateTimeImmutable $time;
    private int $entrances;
    private int $exits;

    public function __construct(DateTimeImmutable $time, int $entrances, int $exits)
    {
        $this->time      = $time;
        $this->entrances = $entrances;
        $this->exits     = $exits;
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
}

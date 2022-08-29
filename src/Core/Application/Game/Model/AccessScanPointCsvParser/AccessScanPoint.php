<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Game\Model\AccessScanPointCsvParser;

use DateTimeImmutable;

class AccessScanPoint
{
    public function __construct(private DateTimeImmutable $time, private int $entrances, private int $exits)
    {
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

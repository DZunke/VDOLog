<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Game\Model\AccessScanPointCsvParser;

use DateTimeImmutable;

class AccessScanPoint
{
    public function __construct(
        public readonly DateTimeImmutable $time,
        public readonly int $entrances,
        public readonly int $exits,
    ) {
    }
}

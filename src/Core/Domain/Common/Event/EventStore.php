<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain\Common\Event;

interface EventStore
{
    /**
     * @return DomainEvent[]
     */
    public function flushEvents(): array;
}

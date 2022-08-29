<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain\Common\Event;

trait EventStoreable
{
    /** @var DomainEvent[] */
    private array $events = [];

    private function raiseEvent(DomainEvent $event): void
    {
        $this->events[] = $event;
    }

    /** @return DomainEvent[] */
    public function flushEvents(): array
    {
        $events       = $this->events;
        $this->events = [];

        return $events;
    }
}

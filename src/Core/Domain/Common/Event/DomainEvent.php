<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain\Common\Event;

use DateTimeImmutable;
use DateTimeInterface;
use Symfony\Contracts\EventDispatcher\Event as SymfonyEvent;

abstract class DomainEvent extends SymfonyEvent
{
    protected DateTimeImmutable $created;

    public function __construct()
    {
        $this->created = new DateTimeImmutable();
    }

    public function getCreated(): DateTimeInterface
    {
        return $this->created;
    }
}

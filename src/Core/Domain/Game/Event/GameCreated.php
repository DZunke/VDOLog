<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain\Game\Event;

use VDOLog\Core\Domain\Common\Event\DomainEvent;
use VDOLog\Core\Domain\Game;

final class GameCreated extends DomainEvent
{
    public function __construct(public readonly Game $game)
    {
        parent::__construct();
    }
}

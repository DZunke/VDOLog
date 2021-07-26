<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain\Game\Event;

use VDOLog\Core\Domain\Common\Event\DomainEvent;
use VDOLog\Core\Domain\Game;

final class GameCreated extends DomainEvent
{
    private Game $game;

    public function __construct(Game $game)
    {
        parent::__construct();

        $this->game = $game;
    }

    public function getGame(): Game
    {
        return $this->game;
    }
}

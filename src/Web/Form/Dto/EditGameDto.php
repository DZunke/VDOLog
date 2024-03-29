<?php

declare(strict_types=1);

namespace VDOLog\Web\Form\Dto;

use VDOLog\Core\Application\Game\EditGame;
use VDOLog\Core\Domain\Game;
use VDOLog\Core\Domain\Location;
use VDOLog\Web\Form\Dto\Game\TimeFrameDto;

final class EditGameDto
{
    private string $id;
    public string $name;
    public Location|null $location;
    public TimeFrameDto $timeFrame;

    public function __construct(Game $game)
    {
        $this->id        = $game->getId();
        $this->name      = $game->getName();
        $this->location  = $game->getLocation();
        $this->timeFrame = TimeFrameDto::fromTimeFrame($game->getTimeFrame());
    }

    public function toCommand(): EditGame
    {
        return new EditGame($this->id, $this->name, $this->timeFrame->toTimeFrame());
    }
}

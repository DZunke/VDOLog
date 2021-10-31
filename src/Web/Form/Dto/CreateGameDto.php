<?php

declare(strict_types=1);

namespace VDOLog\Web\Form\Dto;

use VDOLog\Core\Application\Game\CreateGame;
use VDOLog\Web\Form\Dto\Game\TimeFrameDto;

final class CreateGameDto
{
    public string $name;
    public TimeFrameDto $timeFrame;

    public function __construct()
    {
        $this->timeFrame = new TimeFrameDto();
    }

    public function toCommand(): CreateGame
    {
        return new CreateGame($this->name, $this->timeFrame->toTimeFrame());
    }
}

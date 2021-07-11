<?php

declare(strict_types=1);

namespace VDOLog\Web\Form\Dto;

use VDOLog\Core\Application\Game\CreateGame;

final class CreateGameDto
{
    public string $name;

    public function toCommand(): CreateGame
    {
        return new CreateGame($this->name);
    }
}

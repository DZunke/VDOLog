<?php

declare(strict_types=1);

namespace VDOLog\Web\Form\Dto;

use VDOLog\Core\Application\Game\EditGame;
use VDOLog\Core\Domain\Game;

final class EditGameDto
{
    private string $id;
    public string $name;

    public function __construct(Game $game)
    {
        $this->id   = $game->getId();
        $this->name = $game->getName();
    }

    public function toCommand(): EditGame
    {
        return new EditGame($this->id, $this->name);
    }
}

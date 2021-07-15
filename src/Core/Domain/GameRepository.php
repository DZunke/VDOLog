<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain;

use VDOLog\Core\Domain\Game\Exception\GameNotFound;

interface GameRepository
{
    /**
     * @throws GameNotFound if the game with given id does not exist.
     */
    public function get(string $id): Game;

    public function save(Game $game): void;

    public function delete(string $id): void;
}

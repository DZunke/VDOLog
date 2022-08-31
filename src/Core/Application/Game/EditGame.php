<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Game;

use Assert\Assertion;
use VDOLog\Core\Domain\Game\TimeFrame;

class EditGame
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly TimeFrame $timeFrame,
    ) {
        Assertion::uuid($id, 'A game id must be given to edit it');
        Assertion::notBlank($name, 'A game must not get an empty name');
    }
}

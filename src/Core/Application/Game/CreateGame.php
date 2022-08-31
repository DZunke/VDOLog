<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Game;

use Assert\Assertion;
use VDOLog\Core\Domain\Game\TimeFrame;
use VDOLog\Core\Domain\Location;

class CreateGame
{
    private Location|null $location = null;

    public function __construct(
        public readonly string $name,
        public readonly TimeFrame $timeFrame,
    ) {
        Assertion::notBlank($name, 'A game must have a name');
    }

    public function getLocation(): Location|null
    {
        return $this->location;
    }

    public function withLocation(Location $location): void
    {
        $this->location = $location;
    }
}

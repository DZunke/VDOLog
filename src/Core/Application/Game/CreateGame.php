<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Game;

use Assert\Assertion;
use VDOLog\Core\Domain\Game\TimeFrame;
use VDOLog\Core\Domain\Location;

class CreateGame
{
    private string $name;
    private TimeFrame $timeFrame;
    private ?Location $location = null;

    public function __construct(string $name, TimeFrame $timeFrame)
    {
        Assertion::notBlank($name, 'A game must have a name');

        $this->name      = $name;
        $this->timeFrame = $timeFrame;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function withLocation(Location $location): void
    {
        $this->location = $location;
    }

    public function getTimeFrame(): TimeFrame
    {
        return $this->timeFrame;
    }
}

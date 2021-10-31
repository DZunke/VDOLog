<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Game;

use Assert\Assertion;
use VDOLog\Core\Domain\Game\TimeFrame;

class EditGame
{
    private string $id;
    private string $name;
    private TimeFrame $timeFrame;

    public function __construct(string $id, string $name, TimeFrame $timeFrame)
    {
        Assertion::uuid($id, 'A game id must be given to edit it');
        Assertion::notBlank($name, 'A game must not get an empty name');

        $this->id        = $id;
        $this->name      = $name;
        $this->timeFrame = $timeFrame;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTimeFrame(): TimeFrame
    {
        return $this->timeFrame;
    }
}

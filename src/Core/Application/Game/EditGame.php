<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Game;

use Assert\Assertion;
use VDOLog\Core\Domain\Game\TimeFrame;

class EditGame
{
    public function __construct(private string $id, private string $name, private TimeFrame $timeFrame)
    {
        Assertion::uuid($id, 'A game id must be given to edit it');
        Assertion::notBlank($name, 'A game must not get an empty name');
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

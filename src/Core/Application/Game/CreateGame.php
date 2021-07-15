<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Game;

use Assert\Assertion;

class CreateGame
{
    private string $name;

    public function __construct(string $name)
    {
        Assertion::notBlank($name, 'A game must have a name');

        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}

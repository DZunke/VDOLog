<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Game;

use Assert\Assertion;

final class EditGame
{
    private string $id;
    private string $name;

    public function __construct(string $id, string $name)
    {
        Assertion::notBlank($name, 'There is no game id given');
        Assertion::notBlank($name, 'A game must have a name');

        $this->id   = $id;
        $this->name = $name;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}

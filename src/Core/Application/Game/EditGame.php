<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Game;

use Assert\Assertion;

class EditGame
{
    private string $id;
    private string $name;

    public function __construct(string $id, string $name)
    {
        Assertion::uuid($id, 'A game id must be given to edit it');
        Assertion::notBlank($name, 'A game must not get an empty name');

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

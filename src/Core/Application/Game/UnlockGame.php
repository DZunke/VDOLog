<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Game;

use Assert\Assertion;

final class UnlockGame
{
    private string $id;

    public function __construct(string $id)
    {
        Assertion::notBlank($id, 'A game always have an id');

        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}

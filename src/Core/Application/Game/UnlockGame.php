<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Game;

use Assert\Assertion;

class UnlockGame
{
    private string $id;

    public function __construct(string $id)
    {
        Assertion::uuid($id, 'A valid game id must be given');

        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}

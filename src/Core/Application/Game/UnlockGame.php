<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Game;

use Assert\Assertion;

class UnlockGame
{
    public function __construct(public readonly string $id)
    {
        Assertion::uuid($id, 'A valid game id must be given');
    }
}

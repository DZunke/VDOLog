<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Game;

use Assert\Assertion;

class DeleteGame
{
    public function __construct(private string $id)
    {
        Assertion::uuid($id, 'To delete a game a valid id must be given');
    }

    public function getId(): string
    {
        return $this->id;
    }
}

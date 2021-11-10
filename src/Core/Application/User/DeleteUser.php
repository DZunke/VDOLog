<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\User;

use Assert\Assertion;

class DeleteUser
{
    private string $id;

    public function __construct(string $id)
    {
        Assertion::uuid($id, 'To delete a user a valid id must be given');

        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}

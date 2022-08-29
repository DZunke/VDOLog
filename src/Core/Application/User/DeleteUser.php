<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\User;

use Assert\Assertion;

class DeleteUser
{
    public function __construct(private string $id)
    {
        Assertion::uuid($id, 'To delete a user a valid id must be given');
    }

    public function getId(): string
    {
        return $this->id;
    }
}

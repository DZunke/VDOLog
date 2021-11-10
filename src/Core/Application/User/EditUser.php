<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\User;

use VDOLog\Core\Domain\Common\EMail;

class EditUser
{
    public function __construct(
        private string $id,
        private EMail $email,
        private string $displayName,
        private bool $isAdmin = false,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEMail(): EMail
    {
        return $this->email;
    }

    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    public function getDisplayName(): string
    {
        return $this->displayName;
    }
}

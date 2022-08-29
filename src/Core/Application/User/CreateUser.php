<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\User;

use VDOLog\Core\Domain\Common\EMail;

class CreateUser
{
    private bool $isAdmin = false;

    public function __construct(private EMail $email, private string $plainTextPassword, private string $displayName = '')
    {
    }

    public function asAdmin(): void
    {
        $this->isAdmin = true;
    }

    public function getEMail(): EMail
    {
        return $this->email;
    }

    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    public function getPlainTextPassword(): string
    {
        return $this->plainTextPassword;
    }

    public function getDisplayName(): string
    {
        return $this->displayName;
    }
}

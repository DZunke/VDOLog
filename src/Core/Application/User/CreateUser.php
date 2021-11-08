<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\User;

use VDOLog\Core\Domain\Common\EMail;

class CreateUser
{
    private EMail $email;
    private string $plainTextPassword;
    private bool $isAdmin = false;

    public function __construct(EMail $email, string $plainTextPassword)
    {
        $this->email             = $email;
        $this->plainTextPassword = $plainTextPassword;
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
}

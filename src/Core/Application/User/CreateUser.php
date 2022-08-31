<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\User;

use VDOLog\Core\Domain\Common\EMail;

class CreateUser
{
    private bool $isAdmin = false;

    public function __construct(
        public readonly EMail $email,
        public readonly string $plainTextPassword,
        public readonly string $displayName = '',
    ) {
    }

    public function asAdmin(): void
    {
        $this->isAdmin = true;
    }

    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }
}

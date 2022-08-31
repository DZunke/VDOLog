<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\User;

use VDOLog\Core\Domain\Common\EMail;

class EditUser
{
    public function __construct(
        public readonly string $id,
        public readonly EMail $email,
        public readonly string $displayName,
        public readonly bool $isAdmin = false,
    ) {
    }
}

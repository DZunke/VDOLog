<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\User;

use Assert\Assertion;

class ChangePassword
{
    public function __construct(
        public readonly string $id,
        public readonly string $plainPassword,
    ) {
        Assertion::uuid($id);
        Assertion::notBlank($plainPassword);
    }
}

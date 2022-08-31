<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\User;

use VDOLog\Core\Helper\Assertion;

class UpdateProfile
{
    public function __construct(
        public readonly string $id,
        public readonly string $displayName,
        public readonly bool $enableNotifications,
    ) {
        Assertion::uuid($id);
    }
}

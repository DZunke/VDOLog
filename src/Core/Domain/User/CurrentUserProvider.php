<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain\User;

use VDOLog\Core\Domain\User;

interface CurrentUserProvider
{
    public function hasCurrentUser(): bool;

    public function getCurrentUser(): User;
}

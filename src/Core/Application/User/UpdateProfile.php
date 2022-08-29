<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\User;

use VDOLog\Core\Helper\Assertion;

class UpdateProfile
{
    public function __construct(private string $id, private string $displayName, private bool $enableNotifications)
    {
        Assertion::uuid($id);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    public function enableNotifications(): bool
    {
        return $this->enableNotifications;
    }
}

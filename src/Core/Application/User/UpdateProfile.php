<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\User;

use VDOLog\Core\Helper\Assertion;

class UpdateProfile
{
    private string $id;
    private string $displayName;
    private bool $enableNotifications;

    public function __construct(string $id, string $displayName, bool $enableNotifications)
    {
        Assertion::uuid($id);

        $this->id                  = $id;
        $this->displayName         = $displayName;
        $this->enableNotifications = $enableNotifications;
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

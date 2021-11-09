<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain\User;

use VDOLog\Core\Domain\User;

trait UserCreatable
{
    private User $createdBy;

    public function setCreatedBy(User $user): void
    {
        $this->createdBy = $user;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }
}

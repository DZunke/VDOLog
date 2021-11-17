<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain\User;

use VDOLog\Core\Domain\User;

trait UserEditable
{
    private User $editedBy;

    public function setEditedBy(User $user): void
    {
        $this->editedBy = $user;
    }

    public function getEditedBy(): ?User
    {
        return $this->editedBy;
    }
}

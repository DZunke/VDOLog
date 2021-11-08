<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain\User\Event;

use VDOLog\Core\Domain\Common\Event\DomainEvent;
use VDOLog\Core\Domain\User;

final class UserCreated extends DomainEvent
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;

        parent::__construct();
    }

    public function getUser(): User
    {
        return $this->user;
    }
}

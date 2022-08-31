<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain\User\Event;

use VDOLog\Core\Domain\Common\Event\DomainEvent;
use VDOLog\Core\Domain\User;

final class UserCreated extends DomainEvent
{
    public function __construct(
        public readonly User $user,
    ) {
        parent::__construct();
    }
}

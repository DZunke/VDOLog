<?php

declare(strict_types=1);

namespace VDOLog\Web\Form\Dto;

use VDOLog\Core\Application\User\CreateUser;
use VDOLog\Core\Domain\Common\EMail;

class CreateUserDto
{
    public string $email;
    public string $password;
    public ?bool $isAdmin;

    public function toCommand(): CreateUser
    {
        $command = new CreateUser(new EMail($this->email), $this->password);
        if ($this->isAdmin === true) {
            $command->asAdmin();
        }

        return $command;
    }
}

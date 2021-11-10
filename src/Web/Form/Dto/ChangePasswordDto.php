<?php

declare(strict_types=1);

namespace VDOLog\Web\Form\Dto;

use VDOLog\Core\Application\User\ChangePassword;
use VDOLog\Core\Domain\User;

class ChangePasswordDto
{
    public string $id;
    public string $password;

    private function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function fromObject(User $user): ChangePasswordDto
    {
        return new self($user->getId());
    }

    public function toCommand(): ChangePassword
    {
        return new ChangePassword($this->id, $this->password);
    }
}

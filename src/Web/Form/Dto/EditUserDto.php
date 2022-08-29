<?php

declare(strict_types=1);

namespace VDOLog\Web\Form\Dto;

use VDOLog\Core\Application\User\EditUser;
use VDOLog\Core\Domain\Common\EMail;
use VDOLog\Core\Domain\User;
use VDOLog\Core\Helper\Assertion;

class EditUserDto
{
    public string $email;
    public string $password;
    public bool|null $isAdmin;
    public string $displayName = '';

    private function __construct(public string $id)
    {
        Assertion::uuid($id);
    }

    public static function fromObject(User $user): EditUserDto
    {
        $dto              = new self($user->getId());
        $dto->email       = (string) $user->getEmail();
        $dto->isAdmin     = $user->isAdmin();
        $dto->displayName = $user->getDisplayName();

        return $dto;
    }

    public function toCommand(): EditUser
    {
        return new EditUser($this->id, new EMail($this->email), $this->displayName, (bool) $this->isAdmin);
    }
}

<?php

declare(strict_types=1);

namespace VDOLog\Web\Form\Dto;

use VDOLog\Core\Application\User\ChangePassword;
use VDOLog\Core\Application\User\UpdateProfile;
use VDOLog\Core\Domain\User;
use VDOLog\Core\Helper\Assertion;

use function is_string;
use function strlen;

class UserProfileDto
{
    public string $displayName            = '';
    public string|null $changePassword    = null;
    public bool|null $enableNotifications = false;

    private function __construct(public string $id)
    {
        Assertion::uuid($id);
    }

    public static function fromObject(User $user): UserProfileDto
    {
        $dto                      = new self($user->getId());
        $dto->displayName         = $user->getDisplayName();
        $dto->enableNotifications = $user->notificationsEnabled();

        return $dto;
    }

    public function toUpdateProfileCommand(): UpdateProfile
    {
        return new UpdateProfile($this->id, $this->displayName, (bool) $this->enableNotifications);
    }

    public function toChangePasswordCommand(): ChangePassword|null
    {
        if (! is_string($this->changePassword) || strlen($this->changePassword) === 0) {
            return null;
        }

        return new ChangePassword($this->id, $this->changePassword);
    }
}

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
    public string $id;
    public string $displayName        = '';
    public ?string $changePassword    = null;
    public ?bool $enableNotifications = false;

    private function __construct(string $id)
    {
        Assertion::uuid($id);

        $this->id = $id;
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

    public function toChangePasswordCommand(): ?ChangePassword
    {
        if (! is_string($this->changePassword) || strlen($this->changePassword) === 0) {
            return null;
        }

        return new ChangePassword($this->id, $this->changePassword);
    }
}

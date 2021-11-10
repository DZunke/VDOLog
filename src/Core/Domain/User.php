<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use VDOLog\Core\Domain\Common\EMail;
use VDOLog\Core\Domain\Common\Event\EventStore;
use VDOLog\Core\Domain\Common\Event\EventStoreable;
use VDOLog\Core\Domain\User\Event\UserCreated;
use VDOLog\Core\Domain\User\UserCreatable;
use VDOLog\Core\Helper\Assertion;

class User implements EventStore
{
    use EventStoreable;
    use UserCreatable;

    private string $id;
    private EMail $email;
    private string $password;
    private string $displayName = '';
    private ?DateTimeImmutable $lastLogin;
    private bool $isAdmin = false;

    public function __construct(string $uuid, EMail $email, string $password)
    {
        Assertion::uuid($uuid, 'An user must have an id');
        Assertion::notBlank($password, 'An user must have a password');

        $this->id       = $uuid;
        $this->email    = $email;
        $this->password = $password;

        $this->raiseEvent(new UserCreated($this));
    }

    public static function create(EMail $email, string $password): User
    {
        return new self(Uuid::uuid4()->toString(), $email, $password);
    }

    public function markAdmin(): void
    {
        $this->isAdmin = true;
    }

    public function changeEMail(EMail $mail): void
    {
        if ($this->email->equals($mail)) {
            return;
        }

        $this->email = $mail;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): EMail
    {
        return $this->email;
    }

    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setLastLogin(DateTimeImmutable $lastLogin): void
    {
        $this->lastLogin = $lastLogin;
    }

    public function getLastLogin(): ?DateTimeImmutable
    {
        return $this->lastLogin;
    }

    public function setDisplayName(string $displayName): void
    {
        $this->displayName = $displayName;
    }

    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    public function changeCredentials(string $password): void
    {
        $this->password = $password;
    }
}

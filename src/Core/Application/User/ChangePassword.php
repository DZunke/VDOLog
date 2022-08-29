<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\User;

use Assert\Assertion;

class ChangePassword
{
    public function __construct(private string $id, private string $plainPassword)
    {
        Assertion::uuid($id);
        Assertion::notBlank($plainPassword);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }
}

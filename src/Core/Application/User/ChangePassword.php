<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\User;

use Assert\Assertion;

class ChangePassword
{
    private string $id;
    private string $plainPassword;

    public function __construct(string $id, string $plainPassword)
    {
        Assertion::uuid($id);
        Assertion::notBlank($plainPassword);

        $this->id            = $id;
        $this->plainPassword = $plainPassword;
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

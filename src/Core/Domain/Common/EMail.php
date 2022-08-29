<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain\Common;

use Assert\Assertion;

final class EMail implements \Stringable
{
    public function __construct(private readonly string $email)
    {
        Assertion::email($email, 'The given email is not valid');
    }

    public function equals(EMail $email): bool
    {
        return $this->email === (string) $email;
    }

    public function __toString(): string
    {
        return $this->email;
    }
}

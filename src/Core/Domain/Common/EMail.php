<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain\Common;

use Assert\Assertion;
use Stringable;

final class EMail implements Stringable
{
    private string $email;

    public function __construct(string $email)
    {
        Assertion::email($email, 'The given email is not valid');

        $this->email = $email;
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

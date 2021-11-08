<?php

declare(strict_types=1);

namespace VDOLog\Tests\Unit\Core\Domain;

use Assert\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use VDOLog\Core\Domain\Common\EMail;
use VDOLog\Core\Domain\User;

final class UserTest extends TestCase
{
    public function testUserCouldNotBeCreatedWithoutId(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('An user must have an id');

        $user = new User('', new EMail('foo@bar.baz'), 'foo');
    }

    public function testUserCouldBeCreatedWithId(): void
    {
        $uuid = Uuid::uuid4()->toString();
        $user = new User($uuid, new EMail('foo@bar.baz'), 'foo');

        self::assertSame($uuid, $user->getId());
        self::assertTrue(Uuid::isValid($user->getId()));
    }

    public function testUserWillGetAnIdOnNamedConstructor(): void
    {
        $user = User::create(new EMail('foo@bar.baz'), 'foo');

        self::assertTrue(Uuid::isValid($user->getId()));
    }
}

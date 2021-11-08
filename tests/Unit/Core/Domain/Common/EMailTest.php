<?php

declare(strict_types=1);

namespace VDOLog\Tests\Unit\Core\Domain\Common;

use Assert\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use VDOLog\Core\Domain\Common\EMail;

final class EMailTest extends TestCase
{
    /** @dataProvider provideEMailTests */
    public function testEMailCouldBeCreated(string $email, bool $expectedResult): void
    {
        if ($expectedResult === false) {
            self::expectException(InvalidArgumentException::class);
            self::expectExceptionMessage('The given email is not valid');
        }

        $emailObj = new EMail($email);

        self::assertSame($email, (string) $emailObj);
    }

    /**
     * @return array<int, array{'email': string, 'expected': bool}>
     */
    public function provideEMailTests(): array
    {
        return [
            ['email' => '', 'expected' => false],
            ['email' => 'foo', 'expected' => false],
            ['email' => 'foo@baz', 'expected' => false],
            ['email' => 'foo@bar.baz', 'expected' => true],
        ];
    }
}

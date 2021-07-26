<?php

declare(strict_types=1);

namespace VDOLog\Core\Helper;

use Assert\Assertion as BaseAssertion;
use DateTime;
use Throwable;

use function sprintf;

final class Assertion extends BaseAssertion
{
    public const INVALID_RELATIVE_DATETIME_STRING = 801;

    public static function relativeDateTimeString(
        mixed $value,
        ?string $message = null,
        ?string $propertyPath = null
    ): bool {
        try {
            new DateTime($value);
        } catch (Throwable) {
            $message = sprintf(
                self::generateMessage($message ?? 'Value "%s" is not a relative datetime string.'),
                self::stringify($value)
            );

            throw self::createException($value, $message, self::INVALID_RELATIVE_DATETIME_STRING, $propertyPath);
        }

        return true;
    }
}

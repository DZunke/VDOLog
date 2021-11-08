<?php

declare(strict_types=1);

namespace VDOLog\Core\Infrastructure\Doctrine\Type\Common;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use VDOLog\Core\Domain\Common\EMail;

class EMailType extends StringType
{
    public function getName(): string
    {
        return 'email';
    }

    /** @inheritDoc */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return (string) $value;
    }

    /** @inheritDoc */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new EMail($value);
    }
}

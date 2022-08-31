<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Location;

use VDOLog\Core\Helper\Assertion;

class CreateLocation
{
    /** @param string[] $accessScanners */
    public function __construct(public readonly string $name, public readonly array $accessScanners)
    {
        Assertion::notBlank($name);
        Assertion::allString($accessScanners);
    }
}

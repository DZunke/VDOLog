<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Location;

use VDOLog\Core\Helper\Assertion;

use function array_keys;

class UpdateLocation
{
    /** @param array<string,string> $accessScanners */
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly array $accessScanners,
    ) {
        Assertion::uuid($id);
        Assertion::notBlank($name);
        Assertion::allNotBlank($accessScanners);
        Assertion::allUuid(array_keys($accessScanners));
    }
}

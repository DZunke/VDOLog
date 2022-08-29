<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Location;

use VDOLog\Core\Helper\Assertion;

class CreateLocation
{
    /** @param string[] $accessScanners */
    public function __construct(private string $name, private array $accessScanners)
    {
        Assertion::notBlank($name);
        Assertion::allString($accessScanners);
    }

    public function getName(): string
    {
        return $this->name;
    }

    /** @return string[] */
    public function getAccessScanners(): array
    {
        return $this->accessScanners;
    }
}

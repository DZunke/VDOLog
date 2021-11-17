<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Location;

use VDOLog\Core\Helper\Assertion;

class CreateLocation
{
    private string $name;
    /** @var string[] */
    private array $accessScanners;

    /**
     * @param string[] $accessScanners
     */
    public function __construct(string $name, array $accessScanners)
    {
        Assertion::notBlank($name);
        Assertion::allString($accessScanners);

        $this->name           = $name;
        $this->accessScanners = $accessScanners;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string[]
     */
    public function getAccessScanners(): array
    {
        return $this->accessScanners;
    }
}

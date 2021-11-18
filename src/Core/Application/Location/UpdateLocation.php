<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Location;

use VDOLog\Core\Helper\Assertion;

use function array_keys;

class UpdateLocation
{
    private string $id;
    private string $name;
    /** @var array<string,string> */
    private array $accessScanners;

    /**
     * @param array<string,string> $accessScanners
     */
    public function __construct(string $id, string $name, array $accessScanners)
    {
        Assertion::uuid($id);
        Assertion::notBlank($name);
        Assertion::allNotBlank($accessScanners);
        Assertion::allUuid(array_keys($accessScanners));

        $this->id             = $id;
        $this->name           = $name;
        $this->accessScanners = $accessScanners;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<string,string>
     */
    public function getAccessScanners(): array
    {
        return $this->accessScanners;
    }
}
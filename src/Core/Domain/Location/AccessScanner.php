<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain\Location;

use Assert\Assertion;
use Ramsey\Uuid\Uuid;
use VDOLog\Core\Domain\Location;

class AccessScanner
{
    public function __construct(private string $id, private Location $location, private string $name)
    {
        Assertion::uuid($id);
        Assertion::notBlank($name);
    }

    public static function create(Location $location, string $name): AccessScanner
    {
        return new self(Uuid::uuid4()->toString(), $location, $name);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getLocation(): Location
    {
        return $this->location;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function rename(string $name): void
    {
        $this->name = $name;
    }

    public function equals(AccessScanner $checkScanner): bool
    {
        return $checkScanner->getLocation()->getId() === $this->location->getId()
            && $this->id === $checkScanner->getId();
    }
}

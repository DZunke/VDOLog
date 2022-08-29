<?php

declare(strict_types=1);

namespace VDOLog\Web\Form\Dto\Location;

use VDOLog\Core\Domain\Location\AccessScanner;

class AccessScannerDto
{
    public string|null $id = null;
    public string $name    = '';

    public static function fromAccessScanner(AccessScanner $accessScanner): AccessScannerDto
    {
        $dto       = new self();
        $dto->id   = $accessScanner->getId();
        $dto->name = $accessScanner->getName();

        return $dto;
    }
}

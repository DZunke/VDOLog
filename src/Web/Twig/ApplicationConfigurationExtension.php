<?php

declare(strict_types=1);

namespace VDOLog\Web\Twig;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use VDOLog\Web\Model\ApplicationConfiguration;

final class ApplicationConfigurationExtension extends AbstractExtension implements GlobalsInterface
{
    public function __construct(private ApplicationConfiguration $applicationConfiguration)
    {
    }

    /** @inheritDoc */
    public function getGlobals(): array
    {
        return ['app_config' => $this->applicationConfiguration];
    }
}

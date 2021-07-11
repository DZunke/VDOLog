<?php

declare(strict_types=1);

namespace VDOLog\Web\Twig;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use VDOLog\Web\Model\ApplicationConfiguration;

final class ApplicationConfigurationExtension extends AbstractExtension implements GlobalsInterface
{
    private ApplicationConfiguration $applicationConfiguration;

    public function __construct(ApplicationConfiguration $applicationConfiguration)
    {
        $this->applicationConfiguration = $applicationConfiguration;
    }

    /** @inheritDoc */
    public function getGlobals(): array
    {
        return ['app_config' => $this->applicationConfiguration];
    }
}

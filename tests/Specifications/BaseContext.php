<?php

declare(strict_types=1);

namespace VDOLog\Tests\Specifications;

use Behat\Behat\Context\Context;
use Behat\Testwork\Hook\Scope\HookScope;
use FriendsOfBehat\SymfonyExtension\Context\Environment\InitializedSymfonyExtensionEnvironment;

use function assert;

abstract class BaseContext implements Context
{
    protected function getContext(HookScope $scope, string $contextClass): Context
    {
        $environment = $scope->getEnvironment();
        assert($environment instanceof InitializedSymfonyExtensionEnvironment);

        return $environment->getContext($contextClass);
    }
}

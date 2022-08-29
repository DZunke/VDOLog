<?php

declare(strict_types=1);

namespace VDOLog\Tests\Specifications;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\MinkExtension\Context\MinkContext;

use function assert;

final class SecurityContext extends BaseContext implements Context
{
    private MinkContext $minkContext;

    /** @BeforeScenario */
    public function gatherContexts(BeforeScenarioScope $scope): void
    {
        $minkContext = $this->getContext($scope, MinkContext::class);
        assert($minkContext instanceof MinkContext);

        $this->minkContext = $minkContext;
    }

    /** @Given /^I am authenticated as "([^"]*)" using "([^"]*)"$/ */
    public function iAmAuthenticatedAs(string $email, string $password): void
    {
        $this->minkContext->visit('/login');
        $this->minkContext->fillField('email', $email);
        $this->minkContext->fillField('password', $password);
        $this->minkContext->pressButton('Anmelden');
    }
}

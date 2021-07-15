<?php

declare(strict_types=1);

namespace VDOLog\Tests\Specifications;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;

use function array_keys;
use function array_values;
use function assert;

final class FormContext extends BaseContext implements Context
{
    private MinkContext $minkContext;

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope): void
    {
        $minkContext = $this->getContext($scope, MinkContext::class);
        assert($minkContext instanceof MinkContext);

        $this->minkContext = $minkContext;
    }

    /**
     * @Then I should see form :formName
     */
    public function iShouldSeeForm(string $formName): void
    {
        $this->minkContext->assertElementOnPage('form[name="' . $formName . '"]');
    }

    /**
     * @Then I should not see form :formName
     */
    public function iShouldNotSeeForm(string $formName): void
    {
        $this->minkContext->assertElementNotOnPage('form[name="' . $formName . '"]');
    }

    /**
     * @Then I should see fields in form :formName
     */
    public function iShouldSeeFieldsInForm(string $formName, TableNode $formElements): void
    {
        foreach ($formElements as $formElement) {
            $identifier     = array_keys($formElement)[0];
            $identifierName = array_values($formElement)[0];

            $this->minkContext->assertElementOnPage(
                'form[name="' . $formName . '"] [' . $identifier . '="' . $identifierName . '"]'
            );
        }
    }
}

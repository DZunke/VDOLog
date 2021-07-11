<?php

declare(strict_types=1);

namespace VDOLog\Tests\Specifications;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Mink\Element\NodeElement;
use Behat\MinkExtension\Context\MinkContext;
use Exception;
use Symfony\Component\Routing\RouterInterface;

use function assert;

final class ProtocolContext extends BaseContext implements Context
{
    private RouterInterface $router;
    private MinkContext $minkContext;
    private GameContext $gameContext;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope): void
    {
        $minkContext = $this->getContext($scope, MinkContext::class);
        assert($minkContext instanceof MinkContext);

        $this->minkContext = $minkContext;

        $gameContext = $this->getContext($scope, GameContext::class);
        assert($gameContext instanceof GameContext);

        $this->gameContext = $gameContext;
    }

    /**
     * @Given I am on the protocol page of game named :gameName
     */
    public function iAmOnTheProtocolPageOfGameNamed(string $gameName): void
    {
        $game = $this->gameContext->getGame($gameName);

        $this->minkContext->visit($this->router->generate('protocol_index', ['game' => $game->getId()]));
    }

    /**
     * @Then I should see protocol parent entry from :sender to :recipent with content :content
     */
    public function iShouldSeeProtocolParentEntryFromToWithContent(
        string $sender,
        string $recipent,
        string $content
    ): void {
        $protocolEntries = $this->minkContext->getSession()->getPage()->findAll('css', '.protocol-entry');
        foreach ($protocolEntries as $protocolEntry) {
            $protocolSender = $protocolEntry->find('css', '.protocol-sender');
            if ($protocolSender instanceof NodeElement) {
                $protocolSender = $protocolSender->getText();
            }

            $protocolRecipent = $protocolEntry->find('css', '.protocol-recipent');
            if ($protocolRecipent instanceof NodeElement) {
                $protocolRecipent = $protocolRecipent->getText();
            }

            $protocolContent = $protocolEntry->find('css', '.protocol-content');
            if ($protocolContent instanceof NodeElement) {
                $protocolContent = $protocolContent->getText();
            }

            if ($protocolSender === $sender && $protocolRecipent === $recipent && $protocolContent === $content) {
                return;
            }
        }

        throw new Exception(
            'Missing protocol message from "' . $sender . '" to "' . $recipent . '" with content "' . $content . '"'
        );
    }
}

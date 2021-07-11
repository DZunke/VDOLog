<?php

declare(strict_types=1);

namespace VDOLog\Tests\Specifications;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Mink\Element\NodeElement;
use Behat\MinkExtension\Context\MinkContext;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Component\Routing\RouterInterface;
use VDOLog\Web\Entity\Game;

use function array_key_exists;
use function assert;

final class GameContext extends BaseContext implements Context
{
    private EntityManagerInterface $em;
    private RouterInterface $router;
    private MinkContext $minkContext;

    /** @var Game[] */
    private array $games = [];

    public function __construct(EntityManagerInterface $em, RouterInterface $router)
    {
        $this->em     = $em;
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
    }

    /**
     * @Given There is a game named :name
     */
    public function thereIsAGameNamed(string $name): void
    {
        if (isset($this->games[$name])) {
            return;
        }

        $game = $this->em->getRepository(Game::class)->findOneByName($name);
        if ($game !== null) {
            $this->games[$name] = $game;

            return;
        }

        $game = Game::create($name);
        $this->em->persist($game);
        $this->em->flush();

        $this->games[$name] = $game;
    }

    /**
     * @Given the game named :name is blocked
     */
    public function theGameNamedIsBlocked(string $name): void
    {
        $game = $this->getGame($name);
        $game->setClosedAt(new DateTimeImmutable());

        $this->em->flush();
    }

    public function getGame(string $name): Game
    {
        if (! array_key_exists($name, $this->games)) {
            throw new InvalidArgumentException('Game "' . $name . '" does not exist');
        }

        return $this->games[$name];
    }

    /**
     * @Given I see delete link for game :name
     * @Then  I should see delete link for game :name
     */
    public function iSeeDeleteLinkForGame(string $name): void
    {
        $link = $this->getDeletionLinkForGame($name);
        if ($link === null) {
            throw new InvalidArgumentException('There is no deletion link for game "' . $name . '"');
        }
    }

    /**
     * @Given I not see unblock link for game :name
     * @Then  I should not see unblock link for game :name
     */
    public function iShouldNotSeeUnblockLinkForGame(string $name): void
    {
        $link = $this->getUnblockLinkForGame($name);
        if ($link !== null) {
            throw new InvalidArgumentException(
                'There is a unblock link for game "' . $name . '" but it should not be there'
            );
        }
    }

    /**
     * @Then I should not see edit link for game :name
     */
    public function iShouldNotSeeEditLinkForGame(string $name): void
    {
        $link = $this->getEditLinkForGame($name);
        if ($link !== null) {
            throw new InvalidArgumentException(
                'There is an edit link for game "' . $name . '" but it should not be there'
            );
        }
    }

    /**
     * @Then I should not see block link for game :name
     */
    public function iShouldNotSeeBlockLinkForGame(string $name): void
    {
        $link = $this->getBlockLinkForGame($name);
        if ($link !== null) {
            throw new InvalidArgumentException(
                'There is an block link for game "' . $name . '" but it should not be there'
            );
        }
    }

    /**
     * @Then I should see radio link for game :name
     */
    public function iSeeRadioLinkForGame(string $name): void
    {
        $link = $this->getRadioLinkForGame($name);
        if ($link === null) {
            throw new InvalidArgumentException('There is no radio link for game "' . $name . '"');
        }
    }

    private function getDeletionLinkForGame(string $name): ?NodeElement
    {
        $game = $this->getGame($name);
        $url  = $this->router->generate('game_delete', ['id' => $game->getId()]);

        return $this->getLinkWithUrl('Das Spiel löschen', $url);
    }

    private function getRadioLinkForGame(string $name): ?NodeElement
    {
        $game = $this->getGame($name);
        $url  = $this->router->generate('protocol_index', ['game' => $game->getId()]);

        return $this->getLinkWithUrl('Das Protokoll zum Spiel öffnen', $url);
    }

    private function getExportLinkForGame(string $name): ?NodeElement
    {
        $game = $this->getGame($name);
        $url  = $this->router->generate('game_export', ['id' => $game->getId()]);

        return $this->getLinkWithUrl('Die Daten zum Spiel nach Excel exportieren', $url);
    }

    /**
     * @Then I should see export link for game :name
     */
    public function iShouldSeeExportLinkForGame(string $name): void
    {
        $link = $this->getExportLinkForGame($name);
        if ($link === null) {
            throw new InvalidArgumentException('There is no export link for game "' . $name . '"');
        }
    }

    private function getEditLinkForGame(string $name): ?NodeElement
    {
        $game = $this->getGame($name);
        $url  = $this->router->generate('game_edit', ['id' => $game->getId()]);

        return $this->getLinkWithUrl('Das Spiel bearbeiten', $url);
    }

    /**
     * @Then I should see edit link for game :name
     */
    public function iShouldSeeEditLinkForGame(string $name): void
    {
        $link = $this->getEditLinkForGame($name);
        if ($link === null) {
            throw new InvalidArgumentException('There is no edit link for game "' . $name . '"');
        }
    }

    /**
     * @Then I should see block link for game :name
     */
    public function iShouldSeeBlockLinkForGame(string $name): void
    {
        $link = $this->getBlockLinkForGame($name);
        if ($link === null) {
            throw new InvalidArgumentException('There is no block link for game "' . $name . '"');
        }
    }

    private function getBlockLinkForGame(string $name): ?NodeElement
    {
        $game = $this->getGame($name);
        $url  = $this->router->generate('game_lock', ['id' => $game->getId()]);

        return $this->getLinkWithUrl('Das Spiel vor Bearbeitung sperren', $url);
    }

    private function getLinkWithUrl(string $content, string $url): ?NodeElement
    {
        $allLinks = $this->minkContext->getSession()->getPage()->findAll('named', ['link', $content]);
        foreach ($allLinks as $link) {
            if ($link->getAttribute('href') === $url) {
                return $link;
            }
        }

        return null;
    }

    /**
     * @Then I should see unblock link for game :name
     */
    public function iShouldSeeUnblockLinkForGame(string $name): void
    {
        $link = $this->getUnblockLinkForGame($name);
        if ($link === null) {
            throw new InvalidArgumentException('There is no unblock link for game "' . $name . '"');
        }
    }

    private function getUnblockLinkForGame(string $name): ?NodeElement
    {
        $game = $this->getGame($name);
        $url  = $this->router->generate('game_unlock', ['id' => $game->getId()]);

        return $this->getLinkWithUrl('Das Spiel zur Bearbeitung freigeben', $url);
    }

    /**
     * @When I follow delete link for game :name
     */
    public function iFollowDeleteLinkForGame(string $name): void
    {
        $link = $this->getDeletionLinkForGame($name);
        if ($link === null) {
            throw new InvalidArgumentException('There is no delete link for game "' . $name . '"');
        }

        $link->click();
    }

    /**
     * @When I follow block link for game :name
     */
    public function iFollowBlockLinkForGame(string $name): void
    {
        $link = $this->getBlockLinkForGame($name);
        if ($link === null) {
            throw new InvalidArgumentException('There is no block link for game "' . $name . '"');
        }

        $link->click();
    }

    /**
     * @Then I should be on delete confirmation page for game :name
     */
    public function iShouldBeOnDeleteConfirmationPageForGame(string $name): void
    {
        $game = $this->getGame($name);
        $url  = $this->router->generate('game_delete', ['id' => $game->getId()]);

        $this->minkContext->assertPageAddress($url);
    }
}

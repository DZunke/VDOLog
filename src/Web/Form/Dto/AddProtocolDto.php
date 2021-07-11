<?php

declare(strict_types=1);

namespace VDOLog\Web\Form\Dto;

use VDOLog\Core\Application\Protocol\AddProtocol;
use VDOLog\Core\Domain\Game;
use VDOLog\Core\Domain\Protocol;

final class AddProtocolDto
{
    private string $gameId;

    public string $content   = '';
    public ?Protocol $parent = null;
    public string $sender    = '';
    public string $recipent  = '';

    public function __construct(Game $game)
    {
        $this->gameId = $game->getId();
    }

    public function toCommand(): AddProtocol
    {
        $command = new AddProtocol($this->gameId, $this->content);
        $command->setParent($this->parent);
        $command->setRecipent($this->recipent);
        $command->setSender($this->sender);

        return $command;
    }
}

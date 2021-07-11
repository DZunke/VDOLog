<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Protocol;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use VDOLog\Core\Domain\GameRepository;
use VDOLog\Core\Domain\Protocol;
use VDOLog\Core\Domain\ProtocolRepository;

final class AddProtocolHandler implements MessageHandlerInterface
{
    private ProtocolRepository $protocolRepository;
    private GameRepository $gameRepository;

    public function __construct(ProtocolRepository $protocolRepository, GameRepository $gameRepository)
    {
        $this->protocolRepository = $protocolRepository;
        $this->gameRepository     = $gameRepository;
    }

    public function __invoke(AddProtocol $message): void
    {
        $game = $this->gameRepository->get($message->getGameId());

        $protocol = Protocol::create($game, $message->getContent());
        $protocol->setSender($message->getSender());
        $protocol->setRecipent($message->getRecipent());

        if ($message->getParent() !== null) {
            $protocol->setParent($message->getParent());
        }

        $this->protocolRepository->save($protocol);
    }
}

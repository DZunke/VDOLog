<?php

declare(strict_types=1);

namespace VDOLog\Core\Application\Protocol;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use VDOLog\Core\Domain\GameRepository;
use VDOLog\Core\Domain\Protocol;
use VDOLog\Core\Domain\ProtocolRepository;
use VDOLog\Core\Domain\User\CurrentUserProvider;

final class AddProtocolHandler implements MessageHandlerInterface
{
    public function __construct(
        private ProtocolRepository $protocolRepository,
        private GameRepository $gameRepository,
        private CurrentUserProvider $currentUserProvider,
    ) {
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

        if ($this->currentUserProvider->hasCurrentUser()) {
            $protocol->setCreatedBy($this->currentUserProvider->getCurrentUser());
        }

        $this->protocolRepository->save($protocol);
    }
}

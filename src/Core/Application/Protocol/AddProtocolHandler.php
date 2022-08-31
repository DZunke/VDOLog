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
        private readonly ProtocolRepository $protocolRepository,
        private readonly GameRepository $gameRepository,
        private readonly CurrentUserProvider $currentUserProvider,
    ) {
    }

    public function __invoke(AddProtocol $message): void
    {
        $game = $this->gameRepository->get($message->gameId);

        $protocol = Protocol::create($game, $message->content);
        $protocol->setSender($message->getSender());
        $protocol->setRecipent($message->getRecipient());

        if ($message->getParent() !== null) {
            $protocol->setParent($message->getParent());
        }

        if ($this->currentUserProvider->hasCurrentUser()) {
            $protocol->setCreatedBy($this->currentUserProvider->getCurrentUser());
        }

        $this->protocolRepository->save($protocol);
    }
}

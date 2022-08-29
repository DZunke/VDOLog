<?php

declare(strict_types=1);

namespace VDOLog\Core\Infrastructure\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use VDOLog\Core\Domain\Game;
use VDOLog\Core\Domain\Game\Exception\GameNotFound;
use VDOLog\Core\Domain\GameRepository;

class DoctrineGameRepository implements GameRepository
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function get(string $id): Game
    {
        $game = $this->em->getRepository(Game::class)->find($id);
        if ($game === null) {
            throw GameNotFound::forId($id);
        }

        return $game;
    }

    public function save(Game $game): void
    {
        if (! $this->em->contains($game)) {
            $this->em->persist($game);
        }

        $this->em->flush();
    }

    public function delete(string $id): void
    {
        $game = $this->get($id);

        $this->em->remove($game);
        $this->em->flush();
    }
}

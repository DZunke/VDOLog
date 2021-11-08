<?php

declare(strict_types=1);

namespace VDOLog\Core\Infrastructure\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use VDOLog\Core\Domain\User;
use VDOLog\Core\Domain\UserRepository;

class DoctrineUserRepository implements UserRepository
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function get(string $id): User
    {
        $user = $this->em->getRepository(User::class)->find($id);
        if (! $user instanceof User) {
            throw new InvalidArgumentException('User does not exist');
        }

        return $user;
    }

    public function findByEmail(string $email): ?User
    {
        return $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
    }

    public function save(User $user): void
    {
        if (! $this->em->contains($user)) {
            $this->em->persist($user);
        }

        $this->em->flush();
    }

    public function delete(string $id): void
    {
        $user = $this->get($id);

        $this->em->remove($user);
        $this->em->flush();
    }
}

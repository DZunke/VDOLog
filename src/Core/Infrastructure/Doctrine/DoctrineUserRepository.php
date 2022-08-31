<?php

declare(strict_types=1);

namespace VDOLog\Core\Infrastructure\Doctrine;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use VDOLog\Core\Domain\User;
use VDOLog\Core\Domain\UserRepository;

class DoctrineUserRepository implements UserRepository
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function get(string $id): User
    {
        $user = $this->em->getRepository(User::class)->find($id);
        if (! $user instanceof User) {
            throw new InvalidArgumentException('User does not exist');
        }

        return $user;
    }

    /** @return Collection<int, User> */
    public function findAll(): Collection
    {
        $users = $this->em->getRepository(User::class)->findAll();

        return new ArrayCollection($users);
    }

    public function findByEmail(string $email): User|null
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

    public function updateLastLogin(string $email, DateTimeImmutable|null $lastLogin = null): void
    {
        if ($lastLogin === null) {
            $lastLogin = new DateTimeImmutable();
        }

        $qb = $this->em->createQueryBuilder();
        $qb->update(User::class, 'u');
        $qb->set('u.lastLogin', $qb->expr()->literal($lastLogin->format('Y-m-d H:i:s')));
        $qb->where($qb->expr()->eq('u.email', $qb->expr()->literal($email)));
        $qb->getQuery()->execute();
    }
}

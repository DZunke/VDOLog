<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain;

use Doctrine\Common\Collections\Collection;

interface UserRepository
{
    public function get(string $id): User;

    public function findByEmail(string $email): ?User;

    /** @return Collection<int, User> */
    public function findAll(): Collection;

    public function save(User $user): void;

    public function delete(string $id): void;
}

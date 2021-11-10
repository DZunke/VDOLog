<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain;

use DateTimeImmutable;

interface UserRepository
{
    public function get(string $id): User;

    public function findByEmail(string $email): ?User;

    /** @return iterable<int, User> */
    public function findAll(): iterable;

    public function save(User $user): void;

    public function delete(string $id): void;

    public function updateLastLogin(string $email, ?DateTimeImmutable $lastLogin = null): void;
}

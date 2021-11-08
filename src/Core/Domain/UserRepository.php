<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain;

interface UserRepository
{
    public function get(string $id): User;

    public function findByEmail(string $email): ?User;

    public function save(User $user): void;

    public function delete(string $id): void;
}

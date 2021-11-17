<?php

declare(strict_types=1);

namespace VDOLog\Core\Domain\Common\SynchronizedCollection;

interface SynchronizationPolicy
{
    /**
     * @return mixed added element
     */
    public function handleAdd(mixed $data): mixed;

    /**
     * @return mixed updated element
     */
    public function handleUpdate(mixed $origin, mixed $data): mixed;

    public function handleRemove(mixed $origin): void;
}

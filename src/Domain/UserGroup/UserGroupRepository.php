<?php

declare(strict_types=1);

namespace App\Domain\UserGroup;

interface UserGroupRepository
{
    /**
     * @return UserGroup[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return UserGroup
     * @throws UserNotFoundException
     */
    public function findGroupOfId(int $id): UserGroup;
}

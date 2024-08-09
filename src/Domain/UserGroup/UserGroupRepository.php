<?php

declare(strict_types=1);

namespace App\Domain\UserGroup;

use App\Domain\User\User;

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
    public function findGroupOfId(int $id):? UserGroup;

    /**
     * @param UserGroup $userGroup
     * @return int
     */
    public function create(UserGroup $userGroup):? UserGroup;

    /**
     * @param UserGroup $userGroup
     * @return UserGroup
     */
    public function update(UserGroup $userGroup):? UserGroup;

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * @param string $group_name
     * @return UserGroup
     * @throws UserNotFoundException
     */
    public function findGroupOfName(string $group_name): UserGroup;

}

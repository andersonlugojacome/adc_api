<?php

declare(strict_types=1);

namespace App\Domain\Role;

interface RoleRepository
{
    /**
     * Encuentra todos los usuarios.
     *
     * @return Role[]
     */
    public function findAll(): array;

    /**
     * Encuentra un rol por su ID.
     *
     * @param int $id
     * @return Role
     * @throws RoleNotFoundException
     */
    public function findById(int $roleId): ?Role;

    /**
     * Crea un nuevo rol.
     *
     * @param array $data
     * @return Role
     */
    public function assignPermissionToRole(int $roleId, int $permissionId): void;

    /**
     * Actualiza un rol existente.
     *
     * @param int $id
     * @param array $data
     * @return Role
     */
    public function removePermissionFromRole(int $roleId, int $permissionId): void;

}

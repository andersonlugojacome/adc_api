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
     * Encuentra un role_permissions por su ID.
     *
     * @param int $id
     * @return array
     * @throws RoleNotFoundException
     */
    public function findRolePermissionsById(int $id): ?array;

    /**
     * Encuentra los roles de un usuario.
     *
     * @param int $userId
     * @return Role[]
     */
    public function findRolesByUserId(int $userId): array;
    


    /**
     * Asigna un permiso a un rol.
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

    /**
     * Crea un nuevo rol.
     *
     * @param array $data
     * @return Role
     */
    public function create(array $data): Role;

    /**
     * Actualiza un rol existente.
     *
     * @param int $id
     * @param array $data
     * @return Role
     */
    public function update(int $id, array $data): ?Role;


    /**
     * Elimina un rol.
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void;

    /**
     * Asigna un rol a un user en user_roles.
     *
     * @param int $userId
     * @param int $roleId
     * @return void
     */
    public function assignRoleToUser(int $userId, int $roleId): void;

    /**
     * Remove un rol a un user en user_roles.
     *
     * @param int $userId
     * @param int $roleId
     * @return void
     */
    public function removeRoleFromUser(int $userId, int $roleId): void;


}

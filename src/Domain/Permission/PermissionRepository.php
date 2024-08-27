<?php

declare(strict_types=1);

namespace App\Domain\Permission;

interface PermissionRepository
{
    /**
     * Encuentra todos los permisos.
     *
     * @return array
     */
    public function findAll(): array;

    // Puedes agregar otros métodos según sea necesario, como `findPermissionById`, `createPermission`, etc.
    public function findById(int $permissionId): ?Permission;

     /**
     * Crea un nuevo usuario.
     *
     * @param array $data
     * @return Permission
     */
    public function create(array $data): Permission;

    /**
     * Actualiza un registro existente.
     *
     * @param int $id
     * @param array $data
     * @return Permission
     */
    public function update(int $id, array $data): ?Permission;

    /**
     * Elimina un reggistro por su ID.
     *
     * @param int $id
     */
    public function delete(int $id): void;


}

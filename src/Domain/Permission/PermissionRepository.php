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
    public function findById(int $permissionId): ?array;

}

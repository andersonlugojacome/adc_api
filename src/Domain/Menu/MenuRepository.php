<?php

declare(strict_types=1);

namespace App\Domain\Menu;

interface MenuRepository
{
    /**
     * Encuentra los elementos del menú accesibles para un usuario específico.
     *
     * @param int $userId
     * @return array
     */
    public function findMenuForUser(int $userId): array;

    // Puedes agregar otros métodos según sea necesario, como `findMenuItemById`, `createMenuItem`, etc.
    public function findAll(): array;
    public function findById(int $menuItemId): ?Menu;
    public function create(array $data): Menu;
    public function update(int $menuItemId, array $data): Menu;
    public function delete(int $menuItemId): void;
    // assign and remove menu_permissions 
    public function assignMenuPermission(int $menuItemId, int $permissionId): void;
    public function removeMenuPermission(int $menuItemId, int $permissionId): void;
    public function hasMenuPermission(int $menuItemId, int $permissionId): bool;
    public function findPermissionsByMenuItemsId(int $menuItemId): array;

}

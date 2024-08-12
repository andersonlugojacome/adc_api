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
    public function findById(int $menuItemId): ?array;

}

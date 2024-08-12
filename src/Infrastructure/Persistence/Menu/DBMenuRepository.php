<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Menu;

use App\Domain\Menu\MenuRepository;
use Doctrine\DBAL\Connection;

class DBMenuRepository implements MenuRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * {@inheritdoc}
     */
    public function findMenuForUser(int $userId): array
    {
        $sql = '
        SELECT DISTINCT mi.* 
        FROM menu_items mi
        LEFT JOIN menu_permissions mp ON mi.id = mp.menu_item_id
        LEFT JOIN role_permissions rp ON mp.permission_id = rp.permission_id
        LEFT JOIN user_roles ur ON rp.role_id = ur.role_id
        WHERE 
            (ur.user_id = :user_id AND mi.status = "visible")
            OR mi.id IN (
                SELECT parent_id 
                FROM menu_items 
                WHERE id IN (
                    SELECT mi.id 
                    FROM menu_items mi
                    JOIN menu_permissions mp ON mi.id = mp.menu_item_id
                    JOIN role_permissions rp ON mp.permission_id = rp.permission_id
                    JOIN user_roles ur ON rp.role_id = ur.role_id
                    WHERE ur.user_id = :user_id AND mi.status = "visible"
                )
            )
        ORDER BY mi.parent_id, mi.sort_order ASC';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('user_id', $userId);

        $result = $stmt->executeQuery();

        return $result->fetchAllAssociative() ?: [];
    }


    // Implementar otros métodos según la interfaz MenuRepository
    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        $sql = 'SELECT * FROM menu_items';
        $stmt = $this->connection->executeQuery($sql);
        return $stmt->fetchAllAssociative();
    }
    /**
     * {@inheritdoc}
     */
    public function findById(int $menuItemId): ?array
    {
        $sql = 'SELECT * FROM menu_items WHERE id = :id';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('id', $menuItemId);
        $result = $stmt->executeQuery();


        return $result->fetchAssociative() ?: null;
    }
}

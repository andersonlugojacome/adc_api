<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Menu;

use App\Domain\Menu\Menu;
use App\Domain\Menu\MenuRepository;
use Doctrine\DBAL\Connection;
use App\Domain\Menu\MenuNotFoundException;


class DBMenuRepository implements MenuRepository
{
    private Connection $connection;
    private $nameTable = 'menu_items';


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
        FROM ' . $this->nameTable . ' mi
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
        $sql = 'SELECT * FROM ' . $this->nameTable;
        $stmt = $this->connection->executeQuery($sql);
        return $stmt->fetchAllAssociative();
    }
    /**
     * {@inheritdoc}
     */
    public function findById(int $menuItemId): ?Menu
    {
        try {
            //code...

            $sql = 'SELECT * FROM ' . $this->nameTable . ' WHERE id = :id';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('id', $menuItemId);
            $result = $stmt->executeQuery();
            $data = $result->fetchAssociative();
            return $data ? $this->mapTo($data) : null;
        } catch (\Throwable $th) {
            throw new MenuNotFoundException();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data): Menu
    {
        $sql = 'INSERT INTO ' . $this->nameTable . ' (title, link, parent_id, sort_order, status) VALUES (:title, :link, :parent_id, :sort_order, :status) ';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('title', $data['title']);
        $stmt->bindValue('link', $data['link']);
        $stmt->bindValue('parent_id', empty($data['parent_id']) ? null : $data['parent_id']);
        $stmt->bindValue('sort_order', empty($data['sort_order']) ? 0 :$data['sort_order']);
        $stmt->bindValue('status', $data['status'] ?? 'visible');
        $stmt->executeQuery();

        // $id = $this->connection->lastInsertId();
        return $this->findById((int)$this->connection->lastInsertId());
        // return $this->connection->lastInsertId();
    }

    /**
     * {@inheritdoc}
     */
    public function update(int $menuItemId, array $data): Menu
    {
        $sql = 'UPDATE ' . $this->nameTable . ' SET title = :title, link = :link, parent_id = :parent_id, sort_order = :sort_order, status = :status WHERE id = :id';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('id', $menuItemId);
        $stmt->bindValue('title', $data['title']);
        $stmt->bindValue('link', $data['link']);
        $stmt->bindValue('parent_id', $data['parent_id'] ?? null);
        $stmt->bindValue('sort_order', $data['sort_order'] ?? 0);
        $stmt->bindValue('status', $data['status'] ?? 'visible');
        $stmt->executeQuery();

        $data['id'] = $menuItemId;
        return $this->findById($menuItemId);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(int $menuItemId): void
    {
        $sql = 'DELETE FROM ' . $this->nameTable . ' WHERE id = :id';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('id', $menuItemId);
        $stmt->executeQuery();
    }

    private function mapTo(array $data): Menu
    {
        return new Menu(
            (int) $data['id'],
            $data['title'],
            $data['link'],
            (int) $data['parent_id'],
            (int) $data['sort_order'],
            $data['status'],
            $data['created_at'],
            $data['updated_at']
        );
    }
}

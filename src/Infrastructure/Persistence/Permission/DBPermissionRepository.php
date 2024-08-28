<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Permission;

use App\Domain\Permission\Permission;
use App\Domain\Permission\PermissionRepository;
use Doctrine\DBAL\Connection;

class DBPermissionRepository implements PermissionRepository
{
    private Connection $connection;
    private $nameTable = "permissions";

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        $sql = 'SELECT * FROM ' . $this->nameTable;
        $stmt = $this->connection->executeQuery($sql);
        return $stmt->fetchAllAssociative();
    }

    // Implementar otros métodos según la interfaz PermissionRepository
    /**
     * {@inheritdoc}
     */
    public function findById(int $permissionId): ?Permission
    {
        try {
            $sql = 'SELECT * FROM ' . $this->nameTable . ' WHERE id = :id';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('id', $permissionId);
            $result = $stmt->executeQuery();
            $data = $result->fetchAssociative();
            return $data ? $this->mapTo($data) : null;
        } catch (\Exception $e) {
            throw new \RuntimeException('Error al encontrar el permiso', 0, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data): Permission
    {
        try {
            $sql = 'INSERT INTO ' . $this->nameTable . ' (name, description, status)
                    VALUES (:name, :description, :status)';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('name', $data['name']);
            $stmt->bindValue('description', $data['description']);
            $stmt->bindValue('status', $data['status']);
            //$stmt->executeQuery();
             $stmt->executeQuery();
            $id = (int) $this->connection->lastInsertId();

            return $this->findById($id);
        } catch (\Exception $e) {
            throw new \RuntimeException('Error al crear el permiso', 0, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function update(int $id, array $data): ?Permission
    {
        try {
            $sql = 'UPDATE ' . $this->nameTable . ' SET name = :name, description = :description, status = :status
                    WHERE id = :id';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('name', $data['name']);
            $stmt->bindValue('description', $data['description']);
            $stmt->bindValue('status', $data['status']);
            $stmt->bindValue('id', $id);
            $stmt->executeQuery();
            return $this->findById($id);
        } catch (\Exception $e) {
            throw new \RuntimeException('Error al actualizar ', 0, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete(int $id): void
    {
        try {
            $sql = 'DELETE FROM ' . $this->nameTable . ' WHERE id = :id';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('id', $id);
            $stmt->executeQuery();
        } catch (\Exception $e) {
            throw new \RuntimeException('Error al eliminar ', 0, $e);
        }
    }



    private function mapTo(array $data): Permission
    {
        return new Permission(
            (int) $data['id'],
            
            $data['name'],
            $data['description'],
            $data['status'],
            
            $data['updated_at'],
            $data['created_at']
        );
    }
}

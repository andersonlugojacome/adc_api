<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Permission;

use App\Domain\Permission\PermissionRepository;
use Doctrine\DBAL\Connection;

class DBPermissionRepository implements PermissionRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        $sql = 'SELECT * FROM permissions';
        $stmt = $this->connection->executeQuery($sql);
        return $stmt->fetchAllAssociative();
    }

    // Implementar otros métodos según la interfaz PermissionRepository
    /**
     * {@inheritdoc}
     */
    public function findById(int $permissionId): ?array
    {
        $sql = 'SELECT * FROM permissions WHERE id = :id';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('id', $permissionId);
        $result = $stmt->executeQuery();
        

        return $result->fetchAssociative() ?: null;
    }
}

<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Role;

use App\Domain\Role\Role;
use App\Domain\Role\RoleRepository;
use App\Domain\Role\RoleNotFoundException;
use Doctrine\DBAL\Connection;

class DBRoleRepository implements RoleRepository
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
        $sql = 'SELECT * FROM roles';
        $stmt = $this->connection->executeQuery($sql);
        return $stmt->fetchAllAssociative();
    }

    // Implementar otros métodos según la interfaz RoleRepository
    /**
     * {@inheritdoc}
     */
    public function findById(int $roleId): ?Role
    {
        try {
            $sql = 'SELECT * FROM roles WHERE id = :id';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('id', $roleId);
            $result = $stmt->executeQuery();
            $userData = $result->fetchAssociative();
            return $userData ? $this->mapToRole($userData) : null;
        } catch (\Exception $e) {
            throw new RoleNotFoundException();
        }
    }
    /**
     * {@inheritdoc}
     */
    public function assignPermissionToRole(int $roleId, int $permissionId): void
    {
        $sql = 'INSERT INTO role_permissions (role_id, permission_id) VALUES (:role_id, :permission_id)';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('role_id', $roleId);
        $stmt->bindValue('permission_id', $permissionId);
        $stmt->executeQuery();
    }
    /**
     * {@inheritdoc}
     */
    public function removePermissionFromRole(int $roleId, int $permissionId): void
    {
        $sql = 'DELETE FROM role_permissions WHERE role_id = :role_id AND permission_id = :permission_id';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('role_id', $roleId);
        $stmt->bindValue('permission_id', $permissionId);
        $stmt->executeQuery();
    }
     /**
     * {@inheritdoc}
     */
    private function mapToRole(array $data): array
    {
        return [
            'id' => (int) $data['id'],
            'name' => (string) $data['name'],
            'description' => (string) $data['description'],
            'created_at' => (string) $data['created_at'],
            'updated_at' => (string) $data['updated_at'],
        ];
    }
}

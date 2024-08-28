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
            $data = $result->fetchAssociative();
            return $data ? $this->mapToRole($data) : null;
        } catch (\Exception $e) {
            throw new RoleNotFoundException();
        }
    }
    /**
     * {@inheritdoc}
     */
    public function findRolesByUserId(int $userId): array
    {
        try {
            $sql = 'SELECT r.* FROM roles r
                JOIN user_roles ur ON r.id = ur.role_id
                WHERE ur.user_id = :user_id';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('user_id', $userId);
            $result = $stmt->executeQuery();
            $rolesData = $result->fetchAllAssociative();

            $roles = [];
            foreach ($rolesData as $roleData) {
                $roles[] = $this->mapToRole($roleData);
            }

            return $roles;
        } catch (\Exception $e) {
            throw new \RuntimeException('Error al obtener los roles del usuario', 0, $e);
        }
    }


    // Create a function evaluate if exit a user_roles
    public function existUserRole(int $userId, int $roleId): bool
    {
        $sql = 'SELECT COUNT(*) AS count FROM user_roles WHERE user_id = :user_id AND role_id = :role_id';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('user_id', $userId);
        $stmt->bindValue('role_id', $roleId);
        $result = $stmt->executeQuery();
        $count = $result->fetchAssociative()['count'];
        return $count > 0;
    }


    /**
     * {@inheritdoc}
     */
    public function assignRoleToUser(int $userId, int $roleId): void
    {
        if ($this->existUserRole($userId, $roleId)) {
            return;
        }

        $sql = 'INSERT INTO user_roles (user_id, role_id) VALUES (:user_id, :role_id)';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('user_id', $userId);
        $stmt->bindValue('role_id', $roleId);
        $stmt->executeQuery();
    }

    /**
     * {@inheritdoc}
     */
    public function removeRoleFromUser(int $userId, int $roleId): void
    {
        $sql = 'DELETE FROM user_roles WHERE user_id = :user_id AND role_id = :role_id';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('user_id', $userId);
        $stmt->bindValue('role_id', $roleId);
        $stmt->executeQuery();
    }


    /**
     * {@inheritdoc}
     * findRolePermissionsById
     * 
     */
    public function findRolePermissionsById(int $id): ?array
    {
        try {
            $sql = 'SELECT r.*, p.name as permission_name, p.id as permission_id FROM roles r
            JOIN role_permissions rp ON r.id = rp.role_id
            JOIN permissions p ON rp.permission_id = p.id
            WHERE r.id = :id';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('id', $id);
            $result = $stmt->executeQuery();
            $data = $result->fetchAllAssociative();
            return $data;
        } catch (\Exception $e) {
            throw new RoleNotFoundException();
        }
    }
    // Create a function evaluate if exit a role_persmissions
    public function existRolePermission(int $roleId, int $permissionId): bool
    {
        $sql = 'SELECT COUNT(*) AS count FROM role_permissions WHERE role_id = :role_id AND permission_id = :permission_id';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('role_id', $roleId);
        $stmt->bindValue('permission_id', $permissionId);
        $result = $stmt->executeQuery();
        $count = $result->fetchAssociative()['count'];
        return $count > 0;
    }

    /**
     * {@inheritdoc}
     */
    public function assignPermissionToRole(int $roleId, int $permissionId): void
    {
        if ($this->existRolePermission($roleId, $permissionId)) {
            return;
        }

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
    public function create(array $data): Role
    {
        try {
            $sql = 'INSERT INTO roles (name, status) VALUES (:name, :status)';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('name', $data['name']);
            $stmt->bindValue('status', $data['status']);
            $stmt->executeQuery();
            $id = (int) $this->connection->lastInsertId();

            return $this->findById($id);
        } catch (\Exception $e) {
            throw new \RuntimeException('Error al crear el rol', 0, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function update(int $id, array $data): ?Role
    {
        try {
            $sql = 'UPDATE roles SET name = :name, status = :status WHERE id = :id';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('name', $data['name']);
            $stmt->bindValue('status', $data['status']);
            $stmt->bindValue('id', $id);
            $stmt->executeQuery();
            return $this->findById($id);
        } catch (\Exception $e) {
            throw new \RuntimeException('Error al actualizar el rol', 0, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete(int $id): void
    {
        try {
            $sql = 'DELETE FROM roles WHERE id = :id';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('id', $id);
            $stmt->executeQuery();
        } catch (\Exception $e) {
            throw new \RuntimeException('Error al eliminar el rol', 0, $e);
        }
    }



    /**
     * {@inheritdoc}
     */
    private function mapToRole(array $data): Role
    {
        return  new Role(
            (int) $data['id'],
            $data['name'],
            $data['status'],
            $data['created_at'],
            $data['updated_at'],
        );
    }
}

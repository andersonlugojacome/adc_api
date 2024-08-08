<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\UserGroup;

use App\Domain\UserGroup\UserGroup;
use Doctrine\DBAL\Connection;

class UserGroupRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findAll(): array
    {
        $sql = 'SELECT * FROM user_groups';
        $stmt = $this->connection->executeQuery($sql);
        $groupData = $stmt->fetchAllAssociative();

        return $groupData;
    }

    public function findGroupOfId(int $id): ?UserGroup
    {
        $sql = 'SELECT * FROM user_groups WHERE id = :id';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('id', $id);
        $result = $stmt->executeQuery();
        $groupData = $result->fetchAssociative();

        return $groupData ? $this->mapToUserGroup($groupData) : null;
    }

    public function createGroup(array $data): UserGroup
    {
        $sql = 'INSERT INTO user_groups (group_name, group_level, group_status) 
                VALUES (:group_name, :group_level, :group_status)';
        
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('group_name', $data['group_name']);
        $stmt->bindValue('group_level', $data['group_level']);
        $stmt->bindValue('group_status', $data['group_status']);

        $stmt->executeQuery();

        $id = (int) $this->connection->lastInsertId();
        return $this->findGroupOfId($id);
    }

    public function updateGroup(int $id, array $data): ?UserGroup
    {
        $sql = 'UPDATE user_groups SET 
                group_name = :group_name, 
                group_level = :group_level, 
                group_status = :group_status 
                WHERE id = :id';

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('id', $id);
        $stmt->bindValue('group_name', $data['group_name']);
        $stmt->bindValue('group_level', $data['group_level']);
        $stmt->bindValue('group_status', $data['group_status']);

        $stmt->executeQuery();

        return $this->findGroupOfId($id);
    }

    public function deleteGroup(int $id): void
    {
        $sql = 'DELETE FROM user_groups WHERE id = :id';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('id', $id);
        $stmt->executeQuery();
    }

    private function mapToUserGroup(array $data): UserGroup
    {
        return new UserGroup(
            (int) $data['id'],
            $data['group_name'],
            (int) $data['group_level'],
            $data['group_status']
        );
    }
}

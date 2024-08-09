<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\UserGroup;

use App\Domain\UserGroup\UserGroup;
use App\Domain\UserGroup\UserGroupRepository;
use Doctrine\DBAL\Connection;

class DBUserGroupRepository implements UserGroupRepository
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
        $sql = 'SELECT * FROM user_groups';
        $stmt = $this->connection->executeQuery($sql);
        $groupData = $stmt->fetchAllAssociative();

        return $groupData;
    }
    /**
     * {@inheritdoc}
     */
    public function findGroupOfId(int $id): ?UserGroup
    {
        $sql = 'SELECT * FROM user_groups WHERE id = :id';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('id', $id);
        $result = $stmt->executeQuery();
        $groupData = $result->fetchAssociative();

        return $groupData ? $this->mapToUserGroup($groupData) : null;
    }
    /**
     * {@inheritdoc}
     */
    public function create(UserGroup $data): ?UserGroup
    {
        $sql = 'INSERT INTO user_groups (group_name, group_level, group_status) 
                VALUES (:group_name, :group_level, :group_status)';

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('group_name', $data['group_name']);
        $stmt->bindValue('group_level', $data['group_level']);
        $stmt->bindValue('group_status', $data['group_status']);


        $result = $stmt->executeQuery();


        $id = (int) $this->connection->lastInsertId();
        return  $this->findGroupOfId($id);
    }
    /**
     * {@inheritdoc}
     */
    public function update(UserGroup $data): ?UserGroup
    {
        $sql = 'UPDATE user_groups SET 
                group_name = :group_name, 
                group_level = :group_level, 
                group_status = :group_status 
                WHERE id = :id';

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('id', $data['id']);
        $stmt->bindValue('group_name', $data['group_name']);
        $stmt->bindValue('group_level', $data['group_level']);
        $stmt->bindValue('group_status', $data['group_status']);

        $stmt->executeQuery();

        return $this->findGroupOfId($data['id']);
    }
    /**
     * {@inheritdoc}
     */
    public function delete(int $id): bool
    {
        try {

            $sql = 'DELETE FROM user_groups WHERE id = :id';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('id', $id);
            $stmt->executeQuery();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    /**
     * {@inheritdoc}
     */
    public function findGroupOfName(string $group_name): UserGroup
    {
        $sql = 'SELECT * FROM user_groups WHERE group_name = :group_name';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('group_name', $group_name);
        $result = $stmt->executeQuery();
        $groupData = $result->fetchAssociative();

        return $this->mapToUserGroup($groupData);
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

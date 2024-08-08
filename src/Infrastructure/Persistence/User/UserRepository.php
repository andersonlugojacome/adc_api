<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use App\Domain\User\User;
use Doctrine\DBAL\Connection;
use OpenApi\Annotations as OA;

class UserRepository
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }


    public function findAll(): array
    {
        $sql = 'SELECT * FROM users';
        $stmt = $this->connection->executeQuery($sql);
        $userData = $stmt->fetchAllAssociative();

        return $userData;
    }

    public function findUserOfId($id): ?User
    {
        try {
            $sql = 'SELECT * FROM users WHERE id = :id';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('id', $id);
            $result = $stmt->executeQuery();
            $userData = $result->fetchAssociative();
            return $userData ? $this->mapToUser($userData) : null;
        } catch (\Exception $e) {
            throw new \RuntimeException('Error al buscar el usuario por ID', 0, $e);
        }
        return null;
    }
 
    public function createUser(array $data): int
    {
        try {
            $sql = 'INSERT INTO users (name, lastname, cc, gender, username, email, password, is_active, user_level, usersprivileges_id, is_admin, created_at, last_login)
                    VALUES (:name, :lastname, :cc, :gender, :username, :email, :password, :is_active, :user_level, :usersprivileges_id, :is_admin, :created_at, :last_login)';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('name', $data['name']);
            $stmt->bindValue('lastname', $data['lastname']);
            $stmt->bindValue('cc', $data['cc']);
            $stmt->bindValue('gender', $data['gender']);
            $stmt->bindValue('username', $data['username']);
            $stmt->bindValue('email', $data['email']);
            //Pass es MD5 porque asi lo pide el enunciado
            $stmt->bindValue('password', sha1(md5($data['password'])));
            $stmt->bindValue('is_active', $data['is_active']);
            $stmt->bindValue('user_level', $data['user_level']);
            $stmt->bindValue('usersprivileges_id', $data['usersprivileges_id']);
            $stmt->bindValue('is_admin', $data['is_admin']);
            $stmt->bindValue('created_at', $data['created_at']);
            $stmt->bindValue('last_login', $data['last_login']);
            $stmt->executeQuery();

            return (int) $this->connection->lastInsertId();
        } catch (\Exception $e) {
            throw new \RuntimeException('Error al crear el usuario', 0, $e);
        }
    }
   
    public function updateUser(int $id, array $data)
    {
        try {
            $sql = 'UPDATE users SET name = :name, lastname = :lastname, cc = :cc, gender = :gender, username = :username, email = :email, password = :password, 
                    is_active = :is_active, user_level = :user_level, usersprivileges_id = :usersprivileges_id, is_admin = :is_admin, created_at = :created_at, 
                    last_login = :last_login WHERE id = :id';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('name', $data['name']);
            $stmt->bindValue('lastname', $data['lastname']);
            $stmt->bindValue('cc', $data['cc']);
            $stmt->bindValue('gender', $data['gender']);
            $stmt->bindValue('username', $data['username']);
            $stmt->bindValue('email', $data['email']);
            //if password is not empty, update it
            if (!empty($data['password'])) {
                $stmt->bindValue('password', sha1(md5($data['password'])));
            }
            $stmt->bindValue('is_active', $data['is_active']);
            $stmt->bindValue('user_level', $data['user_level']);
            $stmt->bindValue('usersprivileges_id', $data['usersprivileges_id']);
            $stmt->bindValue('is_admin', $data['is_admin']);
            $stmt->bindValue('created_at', $data['created_at']);
            $stmt->bindValue('last_login', $data['last_login']);
            $stmt->bindValue('id', $id);
            $stmt->executeQuery();
        } catch (\Exception $e) {
            throw new \RuntimeException('Error al actualizar el usuario', 0, $e);
        }
    }

   
    public function deleteUser(int $id)
    {
        try {
            $sql = 'DELETE FROM users WHERE id = :id';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('id', $id);
            $stmt->executeQuery();
        } catch (\Exception $e) {
            throw new \RuntimeException('Error al eliminar el usuario', 0, $e);
        }
    }

    
    public function findUserByUsername(string $username): ?User
    {
        $sql = 'SELECT * FROM users WHERE username = :username';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('username', $username);
        $result = $stmt->executeQuery();
        $userData = $result->fetchAssociative();

        return $userData ? $this->mapToUser($userData) : null;
    }



    private function mapToUser(array $data): User
    {
        return new User(
            (int) $data['id'],
            $data['name'],
            $data['lastname'],
            (int)$data['cc'],
            (int)$data['gender'],
            $data['username'],
            $data['email'],
            $data['password'],
            (int)$data['is_active'],
            (int) $data['user_level'],
            (int) $data['usersprivileges_id'],
            (int)$data['is_admin'],
            $data['created_at'],
            $data['last_login'] ?? null
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Domain\User\UserRepository;
use App\Domain\User\UserNotFoundException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use OpenApi\Annotations as OA;
use Slim\Psr7\Message;

class DBUserRepository implements UserRepository
{
    private Connection $connection;
    private $nameTable = 'users';


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
        $userData = $stmt->fetchAllAssociative();

        return $userData;
    }

    /**
     * {@inheritdoc}
     */
    public function findUserOfId(int $id): User
    {
        try {
            $sql = 'SELECT * FROM users WHERE id = :id';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('id', $id);
            $result = $stmt->executeQuery();
            $userData = $result->fetchAssociative();
            return $userData ? $this->mapToUser($userData) : null;
        } catch (\Exception $e) {
            throw new UserNotFoundException();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createUser(array $data): User
    {
        try {
            $sql = 'INSERT INTO users (name, lastname, cc, gender, username, email, password, is_active, user_level, usersprivileges_id, is_admin, created_at)
                    VALUES (:name, :lastname, :cc, :gender, :username, :email, :password, :is_active, :user_level, :usersprivileges_id, :is_admin, :created_at)';
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
            $stmt->bindValue('user_level', 0);
            $stmt->bindValue('usersprivileges_id', 0);
            $stmt->bindValue('is_admin', $data['is_admin']);
            $stmt->bindValue('created_at', $data['created_at']);
            $stmt->executeQuery();

            return $this->findUserByUsername($data['username']);
        } catch (\Exception $e) {
            throw new \RuntimeException('Error al crear el usuario', 0, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function updateUser(int $id, array $data): ?User
    {
        try {
            $sql = 'UPDATE ' . $this->nameTable . ' SET name = :name, lastname = :lastname, cc = :cc, gender = :gender, username = :username, email = :email,  
                    is_active = :is_active, user_level = :user_level, usersprivileges_id = :usersprivileges_id, is_admin = :is_admin';
                    
                    
                    
            if (!empty($data['password'])) {
                $sql .= ', password = :password';
            }
            $sql .= ' WHERE id = :id';
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
            $stmt->bindValue('user_level', 0);
            $stmt->bindValue('usersprivileges_id', 0);
            $stmt->bindValue('is_admin', 0);
            $stmt->bindValue('id', $id);
            $stmt->executeQuery();
            return $this->findUserOfId($id);
        } catch (\Exception $e) {
            throw new \RuntimeException('Error al actualizar el usuario ' . $e->getMessage() . ' ' . $e->getLine(), 0, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function deleteUser(int $id): void
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

    /**
     * {@inheritdoc}
     */
    public function findUserByUsername(string $username): ?User
    {
        $sql = 'SELECT * FROM users WHERE username = :username';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('username', $username);
        $result = $stmt->executeQuery();
        $userData = $result->fetchAssociative();

        return $userData ? $this->mapToUser($userData) : null;
    }


    /**
     * {@inheritdoc}
     */
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


    //for loginAction
    /**
     * {@inheritdoc}
     */
    public function isAccountLocked(string $username, string $ipAddress): bool
    {
        $sql = 'SELECT locked_until FROM failed_login_attempts 
            WHERE username = :username AND ip_address = :ip_address';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('username', $username);
        $stmt->bindValue('ip_address', $ipAddress);
        $stamt = $stmt->executeQuery();
        $result = $stamt->fetchAssociative();

        if ($result && $result['locked_until'] && strtotime($result['locked_until']) > time()) {
            return true;
        }

        return false;
    }
    /**
     * {@inheritdoc}
     */
    public function registerFailedAttempt(string $username, string $ipAddress): void
    {
        $sql = 'INSERT INTO failed_login_attempts (username, ip_address, attempts) 
            VALUES (:username, :ip_address, 1)
            ON DUPLICATE KEY UPDATE attempts = attempts + 1, last_attempt = CURRENT_TIMESTAMP';

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('username', $username);
        $stmt->bindValue('ip_address', $ipAddress);
        $stmt->executeQuery();

        // Verificar si el número de intentos supera el umbral permitido
        $this->lockAccountIfNeeded($username, $ipAddress);
    }

    public function lockAccountIfNeeded(string $username, string $ipAddress): void
    {
        $maxAttempts = 5; // Número máximo de intentos permitidos
        $lockoutTime = 5 * 60; // Tiempo de bloqueo en segundos (15 minutos)
    
        $sql = 'SELECT attempts FROM failed_login_attempts 
                WHERE username = :username AND ip_address = :ip_address';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('username', $username);
        $stmt->bindValue('ip_address', $ipAddress);
        $stmt = $stmt->executeQuery();
        $result = $stmt->fetchAssociative();
    
        if ($result && $result['attempts'] >= $maxAttempts) {
            $lockUntil = date('Y-m-d H:i:s', time() + $lockoutTime);
            $sql = 'UPDATE failed_login_attempts 
                    SET locked_until = :locked_until 
                    WHERE username = :username AND ip_address = :ip_address';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('locked_until', $lockUntil);
            $stmt->bindValue('username', $username);
            $stmt->bindValue('ip_address', $ipAddress);
            $stmt->executeQuery();
        }
    }
    

    public function resetFailedAttempts(string $username, string $ipAddress): void
    {
        $sql = 'DELETE FROM failed_login_attempts WHERE username = :username AND ip_address = :ip_address';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('username', $username);
        $stmt->bindValue('ip_address', $ipAddress);
        $stmt->executeQuery();
    }
}

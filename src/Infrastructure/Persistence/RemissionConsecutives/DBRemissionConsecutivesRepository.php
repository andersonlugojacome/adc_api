<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\RemissionConsecutives;

use App\Domain\RemissionConsecutives\RemissionConsecutives;
use App\Domain\RemissionConsecutives\RemissionConsecutivesRepository;
use App\Domain\RemissionConsecutives\RemissionConsecutivesNotFoundException;
use Doctrine\DBAL\Connection;
use OpenApi\Annotations as OA;

class DBRemissionConsecutivesRepository implements RemissionConsecutivesRepository
{
    private  $connection;
    //name of the table
    private $table = 'consecutivosderemisiones';

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {

        $sql = 'SELECT * FROM ' . $this->table;
        $stmt = $this->connection->executeQuery($sql);
        $data = $stmt->fetchAllAssociative();
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function findAllByDate(string $begingDate, string $endDate): array
    {
        // valido que las fechas sean correctas y no tenga mas de dos meses de diferencia
        $date1 = new \DateTime($begingDate);
        $date2 = new \DateTime($endDate);
        $diff = $date1->diff($date2);
        if ($diff->m > 2) {
            throw new RemissionConsecutivesNotFoundException();
        }
        $sql = 'SELECT * FROM '. $this->table.'  WHERE created_at BETWEEN :begingDate AND :endDate';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('begingDate', $begingDate);
        $stmt->bindValue('endDate', $endDate);
        $result = $stmt->executeQuery();
        $data = $result->fetchAllAssociative();
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function findOfId(int $id): RemissionConsecutives
    {
        try {
            $sql = 'SELECT * FROM '. $this->table.'  WHERE id = :id';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('id', $id);
            $result = $stmt->executeQuery();
            $data = $result->fetchAssociative();
            return $data ? $this->mapToRemissionConsecutives($data) : null;
        } catch (\Exception $e) {
            throw new RemissionConsecutivesNotFoundException();
        }
        
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data): RemissionConsecutives
    {
        try {
            $sql = 'INSERT INTO '. $this->table.'  (consecutivo, nroescriturapublica, radicado, tipo, user_id, created_at)
                    VALUES (:consecutivo, :nroescriturapublica, :radicado, :tipo, :user_id, :created_at)';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('consecutivo', $data['consecutivo']);
            $stmt->bindValue('nroescriturapublica', $data['nroescriturapublica']);
            $stmt->bindValue('radicado', $data['radicado']);
            $stmt->bindValue('tipo', $data['tipo']);
            $stmt->bindValue('user_id', $data['user_id']);
            $stmt->bindValue('created_at', $data['created_at']);
            $stmt->executeQuery();
            $data['id'] = $this->connection->lastInsertId();
            return $this->mapToRemissionConsecutives($data);
        } catch (\Exception $e) {
            throw new RemissionConsecutivesNotFoundException();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function update(int $id, array $data): ?RemissionConsecutives
    {
        try {
            $sql = 'UPDATE '. $this->table.'  SET consecutivo = :consecutivo, nroescriturapublica = :nroescriturapublica, radicado = :radicado, tipo = :tipo, user_id = :user_id, created_at = :created_at WHERE id = :id';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('id', $id);
            $stmt->bindValue('consecutivo', $data['consecutivo']);
            $stmt->bindValue('nroescriturapublica', $data['nroescriturapublica']);
            $stmt->bindValue('radicado', $data['radicado']);
            $stmt->bindValue('tipo', $data['tipo']);
            $stmt->bindValue('user_id', $data['user_id']);
            $stmt->bindValue('created_at', $data['created_at']);
            $stmt->executeQuery();
            return $this->findOfId($id);
        } catch (\Exception $e) {
            throw new RemissionConsecutivesNotFoundException();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete(int $id): void
    {
        try {
            $sql = 'DELETE FROM '. $this->table.'  WHERE id = :id';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('id', $id);
            $stmt->executeQuery();
        } catch (\Exception $e) {
            throw new RemissionConsecutivesNotFoundException();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function findByConsecutivo(string $consecutivo): ?RemissionConsecutives
    {
        try {
            $sql = 'SELECT * FROM '. $this->table.'  WHERE consecutivo = :consecutivo';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('consecutivo', $consecutivo);
            $result = $stmt->executeQuery();
            $userData = $result->fetchAssociative();
            return $userData ? $this->mapToRemissionConsecutives($userData) : null;
        } catch (\Exception $e) {
            throw new RemissionConsecutivesNotFoundException();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function nextConsecutive(): int
    {
        try {
            // busco el ultimo consecutivo del año actual en la DB
            $sql = 'SELECT MAX(consecutivo) as consecutivo FROM '. $this->table.' where YEAR(created_at) = YEAR(CURDATE())';
            $stmt = $this->connection->prepare($sql);
            $result = $stmt->executeQuery();
            $data = $result->fetchAssociative();
            return (int) $data['consecutivo'] + 1;
        } catch (\Exception $e) {
            throw new RemissionConsecutivesNotFoundException();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function checkConsecutive(int $consecutivo, int $nroescriturapublica, string $dateescritura): array
    {
        try {
            $sql = 'SELECT * FROM '. $this->table.' WHERE (consecutivo = :consecutivo OR nroescriturapublica = :nroescriturapublica) AND YEAR(created_at) = YEAR(:dateescritura)';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('consecutivo', $consecutivo);
            $stmt->bindValue('nroescriturapublica', $nroescriturapublica);
            $stmt->bindValue('dateescritura', $dateescritura);
            $result = $stmt->executeQuery();
            $data = $result->fetchAllAssociative();
            return $data;
        } catch (\Exception $e) {
            throw new \RuntimeException('Error al verificar el consecutivo: ' . $e->getMessage(), 0, $e);
        }
    }
    
    private function mapToRemissionConsecutives(array $data): RemissionConsecutives
    {
        return new RemissionConsecutives(
            (int) $data['id'],
            (int) $data['consecutivo'],
            (int) $data['nroescriturapublica'],
            (int) $data['radicado'],
            $data['tipo'],
            (int) $data['user_id'],
            $data['created_at']
        );
    }
}
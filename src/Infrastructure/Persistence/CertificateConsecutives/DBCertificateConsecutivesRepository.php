<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\CertificateConsecutives;

use App\Domain\CertificateConsecutives\CertificateConsecutives;
use App\Domain\CertificateConsecutives\CertificateConsecutivesRepository;
use App\Domain\CertificateConsecutives\CertificateConsecutivesNotFoundException;
use Doctrine\DBAL\Connection;
use Exception;
use OpenApi\Annotations as OA;

use function PHPUnit\Framework\throwException;

class DBCertificateConsecutivesRepository implements CertificateConsecutivesRepository
{
    private  $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {

        $sql = 'SELECT * FROM consecutivosdecertificados ';
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
        // if ($diff->m > 2) {
        //     throw new CertificateConsecutivesNotFoundException();
        // }
        $sql = 'SELECT * FROM consecutivosdecertificados  WHERE created_at BETWEEN :begingDate AND :endDate';
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
    public function findOfId(int $id): CertificateConsecutives
    {
        try {
            $sql = 'SELECT * FROM consecutivosdecertificados  WHERE id = :id';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('id', $id);
            $result = $stmt->executeQuery();
            $userData = $result->fetchAssociative();
            return $userData ? $this->mapToCertificateConsecutives($userData) : null;
        } catch (\Exception $e) {
            throw new CertificateConsecutivesNotFoundException();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data): CertificateConsecutives
    {
        try {
            $sql = 'INSERT INTO consecutivosdecertificados  (consecutivo, nroescriturapublica, dateescritura, user_id, created_at)
                    VALUES (:consecutivo, :nroescriturapublica, :dateescritura, :user_id, :created_at)';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('consecutivo', $data['consecutivo']);
            $stmt->bindValue('nroescriturapublica', $data['nroescriturapublica']);
            $stmt->bindValue('dateescritura', $data['dateescritura']);
            $stmt->bindValue('user_id', $data['user_id']);
            $stmt->bindValue('created_at', date('Y-m-d H:i:s'));
            $stmt->executeQuery();
            $data['id'] = $this->connection->lastInsertId();
            return $this->mapToCertificateConsecutives($data);
        } catch (\Exception $e) {
            throwException(new Exception($e->getMessage()));
            // throw new CertificateConsecutivesNotFoundException();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function update(int $id, array $data): ?CertificateConsecutives
    {
        try {
            $sql = 'UPDATE consecutivosdecertificados  SET consecutivo = :consecutivo, nroescriturapublica = :nroescriturapublica, dateescritura = :dateescritura, user_id = :user_id WHERE id = :id';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('id', $id);
            $stmt->bindValue('consecutivo', $data['consecutivo']);
            $stmt->bindValue('nroescriturapublica', $data['nroescriturapublica']);
            $stmt->bindValue('dateescritura', $data['dateescritura']);
            $stmt->bindValue('user_id', $data['user_id']);
            // $stmt->bindValue('created_at', date('Y-m-d H:i:s'));)
            $stmt->executeQuery();
            return $this->findOfId($id);
        } catch (\Exception $e) {
            throw new CertificateConsecutivesNotFoundException();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete(int $id): void
    {
        try {
            $sql = 'DELETE FROM consecutivosdecertificados  WHERE id = :id';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('id', $id);
            $stmt->executeQuery();
        } catch (\Exception $e) {
            throw new CertificateConsecutivesNotFoundException();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function findByConsecutivo(string $consecutivo): ?CertificateConsecutives
    {
        try {
            $sql = 'SELECT * FROM consecutivosdecertificados  WHERE consecutivo = :consecutivo ';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('consecutivo', $consecutivo);
            $result = $stmt->executeQuery();
            $data = $result->fetchAssociative();
            return $data ? $this->mapToCertificateConsecutives($data) : null;
        } catch (\Exception $e) {
            throw new CertificateConsecutivesNotFoundException();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function nextConsecutive(): int
    {
        try {
            // busco el ultimo consecutivo del año actual en la DB
            $sql = 'SELECT MAX(consecutivo) as consecutivo FROM consecutivosdecertificados where YEAR(created_at) = YEAR(CURDATE())';
            $stmt = $this->connection->prepare($sql);
            $result = $stmt->executeQuery();
            $data = $result->fetchAssociative();
            return (int) $data['consecutivo'] + 1;
        } catch (\Exception $e) {
            throw new CertificateConsecutivesNotFoundException();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function checkConsecutive(int $consecutivo, string $dateescritura): bool
    {
        try {
            $sql = 'SELECT * FROM consecutivosdecertificados  WHERE consecutivo = :consecutivo AND YEAR(created_at) = YEAR(:dateescritura)';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('consecutivo', $consecutivo);
            $stmt->bindValue('dateescritura', $dateescritura);
            $result = $stmt->executeQuery();
            $userData = $result->fetchAssociative();
            return $userData ? true : false;
        } catch (\Exception $e) {
            throw new Exception($e->getMessage() . ' q: ' . $sql . ' ' . $dateescritura);

            // throw new CertificateConsecutivesNotFoundException();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function checkNroescriturapublica(int $nroescriturapublica, string $dateescritura): ?CertificateConsecutives
    {
        try {
            $sql = 'SELECT * FROM consecutivosdecertificados  WHERE nroescriturapublica = :nroescriturapublica AND YEAR(created_at) = YEAR(:dateescritura)';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('nroescriturapublica', $nroescriturapublica);
            $stmt->bindValue('dateescritura', $dateescritura);
            $result = $stmt->executeQuery();
            $data = $result->fetchAssociative();
            return $data ? $this->mapToCertificateConsecutives($data) : null;
        } catch (\Exception $e) {
            throw new CertificateConsecutivesNotFoundException();
        }
    }



    private function mapToCertificateConsecutives(array $data): CertificateConsecutives
    {
        return new CertificateConsecutives(
            (int) $data['id'],
            (int) $data['consecutivo'],
            (int) $data['nroescriturapublica'],
            $data['dateescritura'],
            $data['user_id'],
            $data['created_at']
        );
    }
}

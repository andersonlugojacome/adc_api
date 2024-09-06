<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\PrintedStickers;

use App\Domain\PrintedStickers\PrintedStickers;
use App\Domain\PrintedStickers\PrintedStickersRepository;
use Doctrine\DBAL\Connection;

class DBPrintedStickersRepository implements PrintedStickersRepository
{
    private Connection $connection;
    private $nameTable = "printed_stickers";

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

    /**
     * {@inheritdoc}
     */
    public function findById(int $printedStickersId): ?PrintedStickers
    {
        try {
            $sql = 'SELECT * FROM ' . $this->nameTable . ' WHERE id = :id';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('id', $printedStickersId);
            $result = $stmt->executeQuery();
            $data = $result->fetchAssociative();
            return $data ? $this->mapTo($data) : null;
        } catch (\Exception $e) {
            throw new \RuntimeException('Error al encontrar el ', 0, $e);
        }
    }

     /**
     * {@inheritdoc}
     */
    public function findByCodigocrypto(string $codigocrypto): ?PrintedStickers
    {
        try {
            $sql = 'SELECT * FROM ' . $this->nameTable . ' WHERE codigocrypto = :codigocrypto';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('codigocrypto', $codigocrypto);
            $result = $stmt->executeQuery();
            $data = $result->fetchAssociative();
            return $data ? $this->mapTo($data) : null;
        } catch (\Exception $e) {
            throw new \RuntimeException('Error al encontrar el ', 0, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data): PrintedStickers
    {
        // Generar el código alfanumérico único de 20 caracteres
        $codigocrypto = $this->generateUniqueCryptoCode();
        try {
            $sql = 'INSERT INTO ' . $this->nameTable . ' (indice, codigocrypto, user_id,notario)
                    VALUES (:indice, :codigocrypto, :user_id, :notario)';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('indice', $data['indice']);
            $stmt->bindValue('codigocrypto', $codigocrypto);
            $stmt->bindValue('user_id', $data['user_id']);
            $stmt->bindValue('notario', $data['notario']);
            $stmt->executeQuery();
            $id = (int) $this->connection->lastInsertId();

            return $this->findById($id);
        } catch (\Exception $e) {
            throw new \RuntimeException('Error al crear el ', 0, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function update(int $id, array $data): ?PrintedStickers
    {
        try {
            $sql = 'UPDATE ' . $this->nameTable . ' SET indice = :indice, codigocrypto = :codigocrypto, user_id = :user_id
                    WHERE id = :id';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('indice', $data['indice']);
            $stmt->bindValue('codigocrypto', $data['codigocrypto']);
            $stmt->bindValue('user_id', $data['user_id']);
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



    private function mapTo(array $data): PrintedStickers
    {
        return new PrintedStickers(
            (int) $data['id'],
            (int) $data['indice'],
            $data['codigocrypto'],
            (int) $data['user_id'],
            $data['notario'],
            $data['created_at'],
            $data['updated_at']
        );
    }

    private function generateCryptoCode(): string
    {
        $length = 20;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
    private function generateUniqueCryptoCode(): string
    {
        do {
            $codigocrypto = $this->generateCryptoCode();
            $exists = $this->checkIfCryptoCodeExists($codigocrypto);
        } while ($exists);

        return $codigocrypto;
    }
    private function checkIfCryptoCodeExists(string $codigocrypto): bool
    {
        $sql = 'SELECT COUNT(*) FROM '. $this->nameTable .' WHERE codigocrypto = :codigocrypto';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('codigocrypto', $codigocrypto);
        $result = $stmt->executeQuery();
        $data = $result->fetchAssociative();


        return $data['COUNT(*)'] > 0; 
    }
}

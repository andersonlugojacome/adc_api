<?php

declare(strict_types=1);

namespace App\Domain\PrintedStickers;

interface PrintedStickersRepository
{
    /**
     * Encuentra todos.
     *
     * @return array
     */
    public function findAll(): array;

     /**
     * Crea un nuevo .
     *
     * @param array $data
     * @return PrintedStickers
     */
    public function create(array $data): PrintedStickers;

    /**
     * Consulta solo uno
     * @param int $id
     * @return PrintedStickers
     */
    public function findById(int $id): ?PrintedStickers;

    /**
     * consult with codigocrypto
     * @param int $codigocrypto
     * @return PrintedStickers
     */
    public function findByCodigocrypto(string $codigocrypto): ?PrintedStickers;






}
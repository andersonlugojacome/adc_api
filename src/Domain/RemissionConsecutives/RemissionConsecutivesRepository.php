<?php

declare(strict_types=1);

namespace App\Domain\RemissionConsecutives;

interface RemissionConsecutivesRepository 
{
    /**
     * Encuentra todos los consecutivos de certificados.
     *
     * @return RemissionConsecutives[]
     */
    public function findAll(): array;

    /**
     * Encuentra todos los consecutivos de remisiones segun la fecha begingDate and endDate.
     * @param string $begingDate
     * @param string $endDate
     * @return RemissionConsecutives[]
     */
    public function findAllByDate(string $begingDate, string $endDate): array;

    /**
     * Encuentra un consecutivo de certificado por su ID.
     *
     * @param int $id
     * @return RemissionConsecutives
     * @throws RemissionConsecutivesNotFoundException
     */
    public function findOfId(int $id): RemissionConsecutives;

    /**
     * Crea un nuevo consecutivo de certificado.
     *
     * @param array $data
     * @return RemissionConsecutives
     */
    public function create(array $data): RemissionConsecutives;

    /**
     * Actualiza un consecutivo de certificado existente.
     *
     * @param int $id
     * @param array $data
     * @return RemissionConsecutives
     */
    public function update(int $id, array $data): ?RemissionConsecutives;

    /**
     * Elimina un consecutivo de certificado por su ID.
     *
     * @param int $id
     */
    public function delete(int $id): void;
    /**
     * Encuentra un consecutivo de certificado por su consecutivo.
     *
     * @param string $consecutivo
     * @return RemissionConsecutives
     * @throws RemissionConsecutivesNotFoundException
     */
    public function findByConsecutivo(string $consecutivo):? RemissionConsecutives;
}
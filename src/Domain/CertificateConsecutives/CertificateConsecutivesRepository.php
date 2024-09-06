<?php

declare(strict_types=1);

namespace App\Domain\CertificateConsecutives;

interface CertificateConsecutivesRepository 
{
    /**
     * Encuentra todos los consecutivos de certificados.
     *
     * @return CertificateConsecutives[]
     */
    public function findAll(): array;

    /**
     * Encuentra todos los consecutivos de certificados segun la fecha begingDate and endDate.
     * @param string $begingDate
     * @param string $endDate
     * @return CertificateConsecutives[]
     */
    public function findAllByDate(string $begingDate, string $endDate): array;

    /**
     * Encuentra un consecutivo de certificado por su ID.
     *
     * @param int $id
     * @return CertificateConsecutives
     * @throws CertificateConsecutivesNotFoundException
     */
    public function findOfId(int $id): CertificateConsecutives;

    /**
     * Crea un nuevo consecutivo de certificado.
     *
     * @param array $data
     * @return CertificateConsecutives
     */
    public function create(array $data): CertificateConsecutives;

    /**
     * Actualiza un consecutivo de certificado existente.
     *
     * @param int $id
     * @param array $data
     * @return CertificateConsecutives
     */
    public function update(int $id, array $data): ?CertificateConsecutives;

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
     * @return CertificateConsecutives
     * @throws CertificateConsecutivesNotFoundException
     */
    public function findByConsecutivo(string $consecutivo):? CertificateConsecutives;

    //nextConsecutive
    /**
     * Encuentra el siguiente consecutivo de certificado.
     *
     * @return int
     */
    public function nextConsecutive(): int;

    //check-consecutives
    /**
     * Verifica si el consecutivo y dateescrituraya existe para ese año
     *
     * @param string $consecutivo
     * @return bool
     */
    public function checkConsecutive(int $consecutivo, int $nroescriturapublica, string $dateescritura): array;


}
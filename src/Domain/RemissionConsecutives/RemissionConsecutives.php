<?php

declare(strict_types=1);

namespace App\Domain\RemissionConsecutives;
use OpenApi\Annotations as OA;

use JsonSerializable;
// ( id, consecutivo, nroescriturapublica radicado, tipo, 	user_id, created_at 	)
/**
 * @OA\Schema(
 *     required={"id", "consecutivo", "nroescriturapublica", "radicado", "user_id", "created_at"},
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="consecutivo", type="integer"),
 *     @OA\Property(property="nroescriturapublica", type="integer"),
 *     @OA\Property(property="radicado", type="integer"),
 *     @OA\Property(property="tipo", type="string"),
 *     @OA\Property(property="user_id", type="integer"),
 *     @OA\Property(property="created_at", type="string", format="date-time")
 * )
 */
class RemissionConsecutives implements JsonSerializable
{
    private ?int $id;
    private int $consecutivo;
    private int $nroescriturapublica;
    private int $radicado;
    private string $tipo;
    private int $user_id;
    private string $created_at;

    public function __construct(
        ?int $id,
        int $consecutivo,
        int $nroescriturapublica,
        int $radicado,
        string $tipo,
        int $user_id,
        string $created_at
    ) {
        $this->id = $id;
        $this->consecutivo = $consecutivo;
        $this->nroescriturapublica = $nroescriturapublica;
        $this->radicado = $radicado;
        $this->tipo = $tipo;
        $this->user_id = $user_id;
        $this->created_at = $created_at;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConsecutivo(): int
    {
        return $this->consecutivo;
    }

    public function getNroescriturapublica(): int
    {
        return $this->nroescriturapublica;
    }

    public function getRadicado(): int
    {
        return $this->radicado;
    }

    public function getTipo(): string
    {
        return $this->tipo;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'consecutivo' => $this->consecutivo,
            'nroescriturapublica' => $this->nroescriturapublica,
            'radicado' => $this->radicado,
            'tipo' => $this->tipo,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at
        ];
    }
}
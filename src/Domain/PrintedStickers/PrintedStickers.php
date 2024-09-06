<?php
declare(strict_types=1);

namespace App\Domain\PrintedStickers;

use OpenApi\Annotations as OA;
use JsonSerializable;

/**
 * @OA\Schema(
 *     schema="PrintedStickers",
 *     required={"id", "indice", "codigocrypto", "user_id"},
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="indice", type="integer"),
 *     @OA\Property(property="codigocrypto", type="string"),
 *     @OA\Property(property="user_id", type="integer"),
 *     @OA\Property(property="notario", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class PrintedStickers implements JsonSerializable
{
    private ?int $id;
    private int $indice;
    private string $codigocrypto;
    private int $user_id;
    private string $notario;
    private string $created_at;
    private ?string $updated_at;

    public function __construct(
        ?int $id,
        int $indice,
        string $codigocrypto,
        int $user_id,
        string $notario,
        string $created_at,
        ?string $updated_at
    ) {
        $this->id = $id;
        $this->indice = $indice;
        $this->codigocrypto = $codigocrypto;
        $this->user_id = $user_id;
        $this->notario = $notario;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIndice(): int
    {
        return $this->indice;
    }

    public function getCodigocrypto(): string
    {
        return $this->codigocrypto;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }
    public function getNotario(): string
    {
        return $this->notario;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }
    public function jsonSerialize():array
    {
        return [
            'id' => $this->id,
            'indice' => $this->indice,
            'codigocrypto' => $this->codigocrypto,
            'user_id' => $this->user_id,
            'notario' => $this->notario,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
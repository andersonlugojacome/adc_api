<?php
declare(strict_types=1);

namespace App\Domain\Permission;

use OpenApi\Annotations as OA;

use JsonSerializable;

// CREATE TABLE permissions (
//     id INT AUTO_INCREMENT PRIMARY KEY,
//     name VARCHAR(255) NOT NULL,
//     description VARCHAR(255) NULL,
//     status ENUM('active', 'inactive') DEFAULT 'active',
//     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
//     updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
// ); add schema for permissions table

/**
 * @OA\Schema(
 *     schema="Permission",
 *     required={"id", "name", "description"},
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="description", type="string"),
 *    @OA\Property(property="status", type="string"),
 *   @OA\Property(property="created_at", type="string", format="date-time"),
 *  @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Permission implements JsonSerializable
{
    private ?int $id;
    private string $name;
    private string $description;
    private string $status;
    private string $created_at;
    private string $updated_at;

    public function __construct(
        ?int $id,
        string $name,
        string $description,
        string $status,
        string $created_at,
        string $updated_at
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->status = $status;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    public function jsonSerialize():array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
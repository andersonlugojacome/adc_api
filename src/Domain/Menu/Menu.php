<?php
declare(strict_types=1);

namespace App\Domain\Menu;

use OpenApi\Annotations as OA;

use JsonSerializable;

// CREATE TABLE menu_items (
//     id INT AUTO_INCREMENT PRIMARY KEY,
//     title VARCHAR(255) NOT NULL,
//     link VARCHAR(255) NOT NULL,
//     parent_id INT DEFAULT NULL, -- For nested menus
//     sort_order INT DEFAULT 0,
//     status ENUM('visible', 'hidden') DEFAULT 'visible',
//     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
//     updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
// );

/**
 * @OA\Schema(
 *     required={"id", "title", "link", "parent_id", "sort_order", "status", "created_at", "updated_at"},
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="link", type="string"),
 *     @OA\Property(property="parent_id", type="integer"),
 *     @OA\Property(property="sort_order", type="integer"),
 *     @OA\Property(property="status", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Menu implements JsonSerializable
{
    private ?int $id;
    private string $title;
    private string $link;
    private ?int $parent_id;
    private int $sort_order;
    private string $status;
    private string $created_at;
    private string $updated_at;

    public function __construct(
        ?int $id,
        string $title,
        string $link,
        ?int $parent_id,
        int $sort_order,
        string $status,
        string $created_at,
        string $updated_at
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->link = $link;
        $this->parent_id = $parent_id;
        $this->sort_order = $sort_order;
        $this->status = $status;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function getParentId(): ?int
    {
        return $this->parent_id;
    }

    public function getSortOrder(): int
    {
        return $this->sort_order;
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

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'link' => $this->link,
            'parent_id' => $this->parent_id,
            'sort_order' => $this->sort_order,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
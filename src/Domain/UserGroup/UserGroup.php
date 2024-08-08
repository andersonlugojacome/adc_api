<?php
declare(strict_types=1);
namespace App\Domain\UserGroup;
use JsonSerializable;
use OpenApi\Annotations as OA;
/**
 * @OA\Schema(
 *     title="UserGroup",
 *     description="UserGroup model",
 *     required={"group_name", "group_level", "group_status"}
 * )
 */
class UserGroup implements JsonSerializable
{
    /**
     * @OA\Property(
     *     description="Id",
     *     title="Id",
     *     type="integer"
     * )
     *
     * @var int|null
     */
    private $id;
    /**
     * @OA\Property(
     *     description="Group name",
     *     title="Group name",
     *     type="string"
     * )
     *
     * @var string
     */
    private $group_name;
    /**
     * @OA\Property(
     *     description="Group level",
     *     title="Group level",
     *     type="integer"
     * )
     *
     * @var int
     */
    private $group_level;
    /**
     * @OA\Property(
     *     description="Group status",
     *     title="Group status",
     *     type="string"
     * )
     *
     * @var string
     */
    private $group_status;
    /**
     * @param int|null  $id
     * @param string  $group_name
     * @param int  $group_level
     * @param string  $group_status
     */
    public function __construct(?int $id, string $group_name, int $group_level, string $group_status)
    {
        $this->id = $id;
        $this->group_name = $group_name;
        $this->group_level = $group_level;
        $this->group_status = $group_status;
    }
    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
    /**
     * @return string
     */
    public function getGroupName(): string
    {
        return $this->group_name;
    }
    /**
     * @return int
     */
    public function getGroupLevel(): int
    {
        return $this->group_level;
    }
    /**
     * @return string
     */
    public function getGroupStatus(): string
    {
        return $this->group_status;
    }
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'group_name' => $this->group_name,
            'group_level' => $this->group_level,
            'group_status' => $this->group_status
        ];
    }
}
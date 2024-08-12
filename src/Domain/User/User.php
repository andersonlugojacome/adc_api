<?php

declare(strict_types=1);

namespace App\Domain\User;
use OpenApi\Annotations as OA;

use JsonSerializable;

/**
 * @OA\Schema(
 *     required={"id", "name", "lastname", "cc", "gender", "username", "email", "password", "is_active", "user_level", "usersprivileges_id", "is_admin", "created_at"},
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="lastname", type="string"),
 *     @OA\Property(property="cc", type="integer"),
 *     @OA\Property(property="gender", type="integer"),
 *     @OA\Property(property="username", type="string"),
 *     @OA\Property(property="email", type="string"),
 *     @OA\Property(property="password", type="string"),
 *     @OA\Property(property="is_active", type="integer"),
 *     @OA\Property(property="user_level", type="integer"),
 *     @OA\Property(property="usersprivileges_id", type="integer"),
 *     @OA\Property(property="is_admin", type="integer"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="last_login", type="string", format="date-time")
 * )
 */
class User implements JsonSerializable
{
    private ?int $id;
    private string $name;
    private string $lastname;
    private int $cc;
    private int $gender;
    private string $username;
    private string $email;
    private string $password;
    private int $is_active;
    private int $user_level;
    private int $usersprivileges_id;
    private int $is_admin;
    private string $created_at;
    private ?string $last_login;

    public function __construct(
        ?int $id,
        string $name,
        string $lastname,
        int $cc,
        int $gender,
        string $username,
        string $email,
        string $password,
        int $is_active,
        int $user_level,
        int $usersprivileges_id,
        int $is_admin,
        string $created_at,
        ?string $last_login
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->lastname = $lastname;
        $this->cc = $cc;
        $this->gender = $gender;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->is_active = $is_active;
        $this->user_level = $user_level;
        $this->usersprivileges_id = $usersprivileges_id;
        $this->is_admin = $is_admin;
        $this->created_at = $created_at;
        $this->last_login = $last_login;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLastName(): string
    {
        return $this->lastname;
    }

    public function getCc(): int
    {
        return $this->cc;
    }

    public function getGender(): int
    {
        return $this->gender;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getIsActive(): int
    {
        return $this->is_active;
    }

    public function getUserLevel(): int
    {
        return $this->user_level;
    }

    public function getUsersPrivilegesId(): int
    {
        return $this->usersprivileges_id;
    }

    public function isAdmin(): int
    {
        return $this->is_admin;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function getLastLogin(): ?string
    {
        return $this->last_login;
    }
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'lastname' => $this->lastname,
            'cc' => $this->cc,
            'gender' => $this->gender,
            'username' => $this->username,
            'email' => $this->email,
            'password' => $this->password,
            'is_active' => $this->is_active,
            'user_level' => $this->user_level,
            'usersprivileges_id' => $this->usersprivileges_id,
            'is_admin' => $this->is_admin,
            'created_at' => $this->created_at,
            'last_login' => $this->last_login,
        ];
    }
}
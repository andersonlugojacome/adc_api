<?php

declare(strict_types=1);

namespace App\Domain\User;

interface UserRepository
{
    /**
     * Encuentra todos los usuarios.
     *
     * @return User[]
     */
    public function findAll(): array;

    /**
     * Encuentra un usuario por su ID.
     *
     * @param int $id
     * @return User
     * @throws UserNotFoundException
     */
    public function findUserOfId(int $id): User;

    /**
     * Crea un nuevo usuario.
     *
     * @param array $data
     * @return User
     */
    public function createUser(array $data): User;

    /**
     * Actualiza un usuario existente.
     *
     * @param int $id
     * @param array $data
     * @return User
     */
    public function updateUser(int $id, array $data): ?User;

    /**
     * Elimina un usuario por su ID.
     *
     * @param int $id
     */
    public function deleteUser(int $id): void;
    /**
     * Encuentra un usuario por su username.
     *
     * @param string $username
     * @return User
     * @throws UserNotFoundException
     */
    public function findUserByUsername(string $username):? User;
}

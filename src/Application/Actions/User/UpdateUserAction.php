<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

/**
 * @OA\Put(
 *     path="/users/{id}",
 *     tags={"users"},
 *     summary="Update user by ID",
 *     description="Updates a user by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of user to update",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="lastname", type="string"),
 *             @OA\Property(property="cc", type="string"),
 *             @OA\Property(property="gender", type="string"),
 *             @OA\Property(property="username", type="string"),
 *             @OA\Property(property="email", type="string", format="email"),
 *             @OA\Property(property="password", type="string", format="password"),
 *             @OA\Property(property="is_active", type="boolean"),
 *             @OA\Property(property="user_level", type="integer"),
 *             @OA\Property(property="is_admin", type="boolean")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User updated successfully",
 *         @OA\JsonContent(ref="#/components/schemas/User")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid request body"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found"
 *     )
 * )
 */

class UpdateUserAction extends UserAction
{
    protected function action(): Response
    {
        $userId = (int) $this->resolveArg('id');
        $data = $this->request->getParsedBody();
        $result = $this->userRepository->updateUser($userId, $data);

        $this->logger->info("User of id `{$userId}` was updated.");

        return $this->respondWithData($result);
    }
}
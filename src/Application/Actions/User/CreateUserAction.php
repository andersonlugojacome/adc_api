<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;
use OpenApi\Annotations as OA;
/**
 * @OA\Post(
 *   path="/users",
 *  summary="Create a new user",
 * description="Create a new user",
 * @OA\RequestBody(
 *     @OA\JsonContent(
 *        type="object",
 *       @OA\Property(property="username", type="string"),
 *      @OA\Property(property="password", type="string"),
 *    @OA\Property(property="email", type="string"),
 *  @OA\Property(property="first_name", type="string"),
 * @OA\Property(property="last_name", type="string"),
 * @OA\Property(property="cc", type="string"),
 * @OA\Property(property="gender", type="string"),
 * @OA\Property(property="is_active", type="string"),
 * @OA\Property(property="is_admin", type="string"),
 * @OA\Property(property="user_level", type="string"),
 * @OA\Property(property="usersprivileges_id", type="string"),

    * )
    * ),
    * @OA\Response(
    *     response=200,
    *     description="User created successfully",
    *     @OA\JsonContent(
    *         type="object",
    *         @OA\Property(property="id", type="string"),
    *         @OA\Property(property="username", type="string"),
    *         @OA\Property(property="email", type="string"),
    *         @OA\Property(property="first_name", type="string"),
    *         @OA\Property(property="last_name", type="string"),
    *         @OA\Property(property="cc", type="string"),
    *         @OA\Property(property="gender", type="string"),
    *         @OA\Property(property="is_active", type="string"),
    *         @OA\Property(property="is_admin", type="string"),
    *         @OA\Property(property="user_level", type="string"),
    *         @OA\Property(property="usersprivileges_id", type="string"),
    *         @OA\Property(property="created_at", type="string"),
    *         @OA\Property(property="last_login", type="string")
    *     )
    * )
    * )
 */

class CreateUserAction extends UserAction
{
    protected function action(): Response
    {
        $data = $this->request->getParsedBody();
        $user = $this->userRepository->createUser($data);

        $this->logger->info("User was created.");

        return $this->respondWithData($user);
    }
}

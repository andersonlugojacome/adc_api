<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     path="/users",
 *     tags={"users"},
 *     summary="Create a new user",
 *     description="Create a new user with the provided details",
 *     @OA\RequestBody(
 *         required=true,
 *         description="User data",
 *         @OA\JsonContent(
 *             required={"name", "lastname", "cc", "gender", "username", "email", "password", "is_active", "user_level", "is_admin"},
 *             @OA\Property(property="name", type="string", example="John Doe"),
 *             @OA\Property(property="lastname", type="string", example="Doe"),
 *             @OA\Property(property="cc", type="string", example="123456789"),
 *             @OA\Property(property="gender", type="string", example="M"),
 *             @OA\Property(property="username", type="string", example="johndoe"),
 *             @OA\Property(property="email", type="string", format="email", example="john@hom.com"),
 *             @OA\Property(property="password", type="string", format="password", example="123456"),
 *             @OA\Property(property="is_active", type="boolean", example=true),
 *             @OA\Property(property="user_level", type="integer", example=1),
 *             @OA\Property(property="is_admin", type="boolean", example=false)
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="name", type="string", example="John"),
 *             @OA\Property(property="lastname", type="string", example="Doe"),
 *             @OA\Property(property="cc", type="string", example="123456789"),
 *             @OA\Property(property="gender", type="string", example="M"),
 *             @OA\Property(property="username", type="string", example="johndoe"),
 *             @OA\Property(property="email", type="string", format="email", example="john@hom.com"),
 *             @OA\Property(property="is_active", type="boolean", example=true),
 *             @OA\Property(property="user_level", type="integer", example=1),
 *             @OA\Property(property="is_admin", type="boolean", example=false)
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input data"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error"
 *     )
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

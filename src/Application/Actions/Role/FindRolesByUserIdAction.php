<?php

declare(strict_types=1);

namespace App\Application\Actions\Role;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Annotations as OA;
/**
 * @OA\Get(
 *     path="/users/{id}/roles",
 *    tags={"Roles"},
 *     summary="Find Roles by User ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the user",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Roles of the user",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Role"))
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found"
 *     )
 * )
 */
class FindRolesByUserIdAction extends RoleAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $userId = (int) $this->args['id'];

        $this->logger->info("Roles of user {$userId} are being viewed.");

        $roles = $this->roleRepository->findRolesByUserId($userId);

        return $this->respondWithData($roles);
    }
}
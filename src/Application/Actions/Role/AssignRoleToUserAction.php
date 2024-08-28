<?php

declare(strict_types=1);

namespace App\Application\Actions\Role;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Annotations as OA;
/**
 * @OA\Post(
 *     path="/users/{id}/roles",
 *    tags={"Roles"},
 *     summary="Assign Role to User",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the user",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="roleId", type="integer")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Role assigned to user",
 *         @OA\JsonContent(ref="#/components/schemas/Role")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found"
 *     )
 * )
 */
class AssignRoleToUserAction extends RoleAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $userId = (int) $this->args['id'];
        $roleId = (int) $this->request->getParsedBody()['roleId'];

        $this->logger->info("Role of id {$roleId} is being assigned to user of id {$userId}.");

        $this->roleRepository->assignRoleToUser($userId, $roleId);

        return $this->respondWithData(null, 201);
    }
}
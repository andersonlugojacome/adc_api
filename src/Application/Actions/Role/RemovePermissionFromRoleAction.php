<?php

declare(strict_types=1);

namespace App\Application\Actions\Role;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Annotations as OA;
/**
 * @OA\Delete(
 *     path="/roles/{id}/permissions",
 *    tags={"Roles"},
 *     summary="Remove Permission from Role",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the role",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="permissionId", type="integer")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Permission removed from role",
 *         @OA\JsonContent(ref="#/components/schemas/Role")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Role not found"
 *     )
 * )
 */
class RemovePermissionFromRoleAction extends RoleAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $roleId = (int) $this->args['id'];
        $permissionId = (int) $this->request->getParsedBody()['permissionId'];

        $this->logger->info("Permission of id {$permissionId} is being removed from role of id {$roleId}.");

        $this->roleRepository->removePermissionFromRole($roleId, $permissionId);

        return $this->respondWithData(null, 201);
    }
}
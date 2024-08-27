<?php

declare(strict_types=1);

namespace App\Application\Actions\Role;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Annotations as OA;
/**
 * @OA\Get(
 *     path="/roles/{id}/permissions",
 *    tags={"Roles"},
 *     summary="Find Role Permissions by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the role",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Role with permissions",
 *         @OA\JsonContent(ref="#/components/schemas/Role")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Role not found"
 *     )
 * )
 */
class FindRolePermissionsByIdAction extends RoleAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $id = (int) $this->args['id'];

        $this->logger->info("Role of id {$id} is being viewed.");

        $role = $this->roleRepository->findRolePermissionsById($id);

        if ($role === null) {
            return $this->logger->error('Role not found.');
        }

        return $this->respondWithData($role);
    }
}
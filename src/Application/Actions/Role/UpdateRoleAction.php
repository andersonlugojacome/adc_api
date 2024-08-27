<?php

declare(strict_types=1);

namespace App\Application\Actions\Role;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Annotations as OA;
/**
 * @OA\Put(
 *     path="/roles/{id}",
 *    tags={"Roles"},
 *     summary="Update a Role",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the role to update",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/Role")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Updated role",
 *         @OA\JsonContent(ref="#/components/schemas/Role")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Role not found"
 *     )
 * )
 */
class UpdateRoleAction extends RoleAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $id = (int) $this->args['id'];
        $role = $this->request->getParsedBody();

        $this->logger->info("Role of id {$id} is being updated.");

        $role = $this->roleRepository->update($id, $role);

        if ($role === null) {
            return $this->logger->error("Role of id {$id} not found.");
            
        }

        return $this->respondWithData($role);
    }
}

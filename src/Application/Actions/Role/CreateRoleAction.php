<?php

declare(strict_types=1);

namespace App\Application\Actions\Role;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Annotations as OA;
/**
 * @OA\Post(
 *     path="/roles",
 *    tags={"Roles"},
 *     summary="Create a new Role",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/Role")
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Created role",
 *         @OA\JsonContent(ref="#/components/schemas/Role")
 *     )
 * )
 */
class CreateRoleAction extends RoleAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $role = $this->request->getParsedBody();

        $this->logger->info("Role is being created.");

        $role = $this->roleRepository->create($role);

        return $this->respondWithData($role, 201);
    }
}
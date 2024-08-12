<?php

declare(strict_types=1);

namespace App\Application\Actions\Role;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Annotations as OA;
/**
 * @OA\Get(
 *     path="/roles",
 *    tags={"Roles"},
 *     summary="List all Roles",
 *     @OA\Response(
 *         response=200,
 *         description="List of roles",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Role"))
 *     )
 * )
 */
class ListRolesAction extends RoleAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $users = $this->roleRepository->findAll();
        
        

        $this->logger->info("Roles list was viewed.");

        return $this->respondWithData($users);
    }
}

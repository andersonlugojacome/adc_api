<?php

declare(strict_types=1);

namespace App\Application\Actions\Permission;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Annotations as OA;
/**
 * @OA\Get(
 *     path="/permissions",
 *     tags={"Permissions"},
 *     summary="List all permissions",
 *     security={{"bearerAuth": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="List of permissions",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Permission"))
 *     )
 * )
 */
class ListPermissionsAction extends PermissionAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $users = $this->repository->findAll();
        $this->logger->info("Permissions list was viewed.");

        return $this->respondWithData($users);
    }
}

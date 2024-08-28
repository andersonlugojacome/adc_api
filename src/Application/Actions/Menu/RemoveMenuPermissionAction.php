<?php

declare(strict_types=1);

namespace App\Application\Actions\Menu;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Delete(
 *     path="/menus/{id}/permissions",
 *     tags={"Menus"},
 *     summary="Remove Permission from Menu",
 *     description="Remove a Permission from a Menu",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of Menu",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Permission data",
 *         @OA\JsonContent(
 *             required={"permission_id"},
 *             @OA\Property(property="permission_id", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Successful operation"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input data"
 *     )
 * )
 */
class RemoveMenuPermissionAction extends MenuAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $menuItemId = (int) $this->resolveArg('id');
        $data = $this->request->getParsedBody();
        $permissionId = (int) $this->resolveArg('permissionId');

        $this->menuRepository->removeMenuPermission($menuItemId, $permissionId);

        $this->logger->info("Permission of id `{$permissionId}` was removed from Menu of id `{$menuItemId}`.");

        return $this->respondWithData(null, 201);
    }
}
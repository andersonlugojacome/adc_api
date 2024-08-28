<?php

declare(strict_types=1);

namespace App\Application\Actions\Menu;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/menus/{id}/permissions",
 *     tags={"Menus"},
 *     summary="Find Permissions by Menu Item ID",
 *     description="Find all Permissions associated with a specific Menu Item",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of Menu Item",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of Permissions",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Permission"))
 *     )
 * )
 */
class FindPermissionsByMenuItemsIdAction extends MenuAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $menuItemId = (int) $this->resolveArg('id');
        $permissions = $this->menuRepository->findPermissionsByMenuItemsId($menuItemId);

        $this->logger->info("Permissions of Menu Item of id `{$menuItemId}` were viewed.");

        return $this->respondWithData($permissions);
    }
}
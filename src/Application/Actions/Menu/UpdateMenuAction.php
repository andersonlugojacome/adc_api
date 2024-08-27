<?php

declare(strict_types=1);

namespace App\Application\Actions\Menu;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Put(
 *     path="/menus/{id}",
 *     tags={"Menus"},
 *     summary="Update a Menu",
 *     description="Update a Menu with the provided details",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of Menu to update",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Menu data",
 *         @OA\JsonContent(
 *             required={"title", "link", "parent_id", "sort_order", "status"},
 *             @OA\Property(property="title", type="string", example="Menu Title"),
 *             @OA\Property(property="link", type="string", example="/path/to/menu"),
 *             @OA\Property(property="parent_id", type="integer", example=null),
 *             @OA\Property(property="sort_order", type="integer", example=0),
 *             @OA\Property(property="status", type="string", example="visible")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="title", type="string", example="Menu Title"),
 *             @OA\Property(property="link", type="string", example="/path/to/menu"),
 *             @OA\Property(property="parent_id", type="integer", example=null),
 *             @OA\Property(property="sort_order", type="integer", example=0),
 *             @OA\Property(property="status", type="string", example="visible")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input data"
 *     )
 * )
 */
class UpdateMenuAction extends MenuAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $menuId = (int) $this->resolveArg('id');
        $data = $this->request->getParsedBody();
        $menu = $this->menuRepository->update($menuId, $data);

        $this->logger->info("Menu of id `{$menuId}` was updated.");

        return $this->respondWithData($menu);
    }
}
<?php

declare(strict_types=1);

namespace App\Application\Actions\Menu;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     path="/menus",
 *     tags={"Menus"},
 *     summary="Create a new Menu",
 *     description="Create a new Menu with the provided details",
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
 *         response=201,
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
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error"
 *     )
 * )
 */
class CreateMenuAction extends MenuAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $data = $this->request->getParsedBody();
        $menu = $this->menuRepository->create($data);

        $this->logger->info("Menu was created.");

        return $this->respondWithData($menu);
    }
}
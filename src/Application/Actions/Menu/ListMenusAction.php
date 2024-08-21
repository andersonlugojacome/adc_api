<?php

declare(strict_types=1);

namespace App\Application\Actions\Menu;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/menus",
 *    tags={"Menus"},
 *     summary="List all Menus",
 *     @OA\Response(
 *         response=200,
 *         description="List of menus",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Menu"))
 *     )
 * )
 */
class ListMenusAction extends MenuAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $data = $this->menuRepository->findAll();
        $this->logger->info("Menus list was viewed.");

        return $this->respondWithData($data);
    }
}

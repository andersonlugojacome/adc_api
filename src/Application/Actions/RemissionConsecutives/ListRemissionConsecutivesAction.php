<?php

declare(strict_types=1);

namespace App\Application\Actions\RemissionConsecutives;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/remission-consecutives",
 *     tags={"remission-consecutives"},
 *     summary="List all remission consecutives",
 *     description="Retrieve a list of all remission consecutives",
 *     @OA\Response(
 *         response=200,
 *         description="A list of remission consecutives",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/RemissionConsecutives")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error"
 *     )
 * )
 */
class ListRemissionConsecutivesAction extends RemissionConsecutivesAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $remissionConsecutives = $this->repository->findAll();

        $this->logger->info("RemissionConsecutives list was viewed.");

        return $this->respondWithData($remissionConsecutives);
    }
}

<?php

declare(strict_types=1);

namespace App\Application\Actions\RemissionConsecutives;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/remission-consecutives/next-consecutive",
 *     tags={"remission-consecutives"},
 *     summary="Get the next consecutive number",
 *     description="Retrieve the next available consecutive number for remission consecutives",
 *     @OA\Response(
 *         response=200,
 *         description="Next consecutive number",
 *         @OA\JsonContent(type="integer")
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error"
 *     )
 * )
 */
class NextRemissionConsecutivesAction extends RemissionConsecutivesAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $nextConsecutive = $this->repository->nextConsecutive();

        $this->logger->info("Next consecutive number was viewed.");

        return $this->respondWithData($nextConsecutive);
    }
}
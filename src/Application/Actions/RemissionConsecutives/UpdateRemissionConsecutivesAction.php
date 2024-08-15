<?php

declare(strict_types=1);

namespace App\Application\Actions\RemissionConsecutives;

use Psr\Http\Message\ResponseInterface as Response;
use OpenApi\Annotations as OA;

/**
 * @OA\Put(
 *     path="/remission-consecutives/{id}",
 *     tags={"remission-consecutives"},
 *     summary="Edit a remission consecutive",
 *     description="Update the details of a specific remission consecutive by its ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the remission consecutive to update",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Updated data for the remission consecutive",
 *         @OA\JsonContent(
 *             @OA\Property(property="consecutivo", type="integer", example=12345),
 *             @OA\Property(property="nroescriturapublica", type="integer", example=67890),
 *             @OA\Property(property="radicado", type="integer", example=54321),
 *             @OA\Property(property="tipo", type="string", example="Tipo A"),
 *             @OA\Property(property="user_id", type="integer", example=1),
 *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-08-15T00:00:00Z")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="consecutivo", type="integer", example=12345),
 *             @OA\Property(property="nroescriturapublica", type="integer", example=67890),
 *             @OA\Property(property="radicado", type="integer", example=54321),
 *             @OA\Property(property="tipo", type="string", example="Tipo A"),
 *             @OA\Property(property="user_id", type="integer", example=1),
 *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-08-15T00:00:00Z")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input data"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Remission consecutive not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error"
 *     )
 * )
 */

class UpdateRemissionConsecutivesAction extends RemissionConsecutivesAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $remissionConsecutivesId = (int) $this->resolveArg('id');
        $data = $this->request->getParsedBody();

        $remissionConsecutives = $this->repository->update($remissionConsecutivesId, $data);

        $this->logger->info("RemissionConsecutives of id `{$remissionConsecutivesId}` was edited.");

        return $this->respondWithData($remissionConsecutives);
    }
}

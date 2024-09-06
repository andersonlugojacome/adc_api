<?php

declare(strict_types=1);

namespace App\Application\Actions\RemissionConsecutives;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     path="/remission-consecutives/check-consecutive",
 *     tags={"remission-consecutives"},
 *     summary="Check if consecutive number exists",
 *     description="Verifies if the consecutive number and dateescrituraya exist for that year",
 *     @OA\RequestBody(
 *         required=true,
 *         description="Data to check",
 *         @OA\JsonContent(
 *             @OA\Property(property="consecutivo", type="integer", example=12345),
 *             @OA\Property(property="nroescriturapublica", type="integer", example=67890),
 *             @OA\Property(property="dateescritura", type="string", format="date", example="2024-08-15")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Consecutive number exists",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/RemissionConsecutives")
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
class CheckRemissionConsecutivesAction extends RemissionConsecutivesAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $data = $this->request->getParsedBody();
        $consecutivo = $data['consecutivo'];
        $nroescriturapublica = $data['nroescriturapublica'];
        $dateescritura = $data['dateescritura'];

        $remissionConsecutives = $this->repository->checkConsecutive($consecutivo, $nroescriturapublica, $dateescritura);

        $this->logger->info("RemissionConsecutives was checked.");

        return $this->respondWithData($remissionConsecutives);
    }
}
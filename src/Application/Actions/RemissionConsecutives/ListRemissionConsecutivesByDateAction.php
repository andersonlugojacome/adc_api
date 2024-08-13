<?php
declare(strict_types=1);

namespace App\Application\Actions\RemissionConsecutives;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/remission-consecutives/{begingDate}/{endDate}",
 *     tags={"remission-consecutives"},
 *     summary="List remissionConsecutives by date",
 *     description="Returns a list of remissionConsecutives by date",
 *     @OA\Parameter(
 *         name="begingDate",
 *         in="path",
 *         description="Beging date of remissionConsecutives to return",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="endDate",
 *         in="path",
 *         description="End date of remissionConsecutives to return",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="RemissionConsecutives response",
 *         @OA\JsonContent(ref="#/components/schemas/RemissionConsecutives")
 *     )
 * )
 */

class ListRemissionConsecutivesByDateAction extends RemissionConsecutivesAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $begingDate = $this->resolveArg('begingDate');
        $endDate = $this->resolveArg('endDate');
        $remissionConsecutives = $this->repository->findAllByDate($begingDate, $endDate);

        $this->logger->info("RemissionConsecutives list date Ranger was viewed.");

        return $this->respondWithData($remissionConsecutives);
    }
}
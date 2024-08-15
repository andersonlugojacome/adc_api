<?php

declare(strict_types=1);

namespace App\Application\Actions\RemissionConsecutives;

use Psr\Http\Message\ResponseInterface as Response;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *    path="/remission-consecutives",
 *    tags={"remission-consecutives"},
 *    summary="Create a new remission-consecutives",
 *    description="Create a new remission-consecutives",
 *    @OA\RequestBody(
 *      required=true,
 *        description="RemissionConsecutives data",
 *        @OA\JsonContent(
 *            required={"consecutivo", "nroescriturapublica", "radicado", "tipo", "user_id"},
 *            @OA\Property(property="consecutivo", type="string", format="string", example="123456789"),
 *            @OA\Property(property="nroescriturapublica", type="string", format="string", example="123456789"),
 *            @OA\Property(property="radicado", type="string", format="string", example="123456789"),
 *            @OA\Property(property="tipo", type="string", format="string", example="123456789"),
 *            @OA\Property(property="user_id", type="integer", format="int64", example=1)
 *        )
 *    ),
 *    @OA\Response(
 *        response="201",
 *        description="Successful operation",
 *        @OA\JsonContent(
 *            @OA\Property(property="id", type="integer", format="int64", example=1),
 *            @OA\Property(property="consecutivo", type="string", format="string", example="123456789"),
 *            @OA\Property(property="nroescriturapublica", type="string", format="string", example="123456789"),
 *            @OA\Property(property="radicado", type="string", format="string", example="123456789"),
 *            @OA\Property(property="tipo", type="string", format="string", example="123456789"),
 *            @OA\Property(property="user_id", type="integer", format="int64", example=1)
 *        )
 *    ),
 *    @OA\Response(
 *        response="400",
 *        description="Invalid data",
 *    ),
 *    @OA\Response(
 *        response="500",
 *        description="Internal server error",
 *    )
 * )
 */

class CreateRemissionConsecutivesAction extends RemissionConsecutivesAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $data = $this->request->getParsedBody();

        $remissionConsecutives = $this->repository->create($data);
        $id = $remissionConsecutives->getId();

        $this->logger->info("RemissionConsecutives of id `{$id}` was created.");

        return $this->respondWithData($remissionConsecutives);
    }
}

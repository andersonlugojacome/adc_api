<?php

declare(strict_types=1);

namespace App\Application\Actions\PrintedStickers;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

/**
 * @OA\Post(
 *     path="/printed-stickers",
 *     summary="Create a new printed sticker Firma registra SIGNO",
 *     operationId="createPrintedSticker",
 *     tags={"Printed Stickers"},
 *     security={{"bearerAuth": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="indice", type="integer"),
 *             @OA\Property(property="codigocrypto", type="string"),
 *             @OA\Property(property="user_id", type="integer")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Printed sticker created successfully",
 *         @OA\JsonContent(ref="#/components/schemas/PrintedStickers")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid request body"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 */

class CreatePrintedStickerAction extends PrintedStickersAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $data = $this->request->getParsedBody();

        if (empty($data['indice']) || empty($data['user_id'])) {
            throw new HttpBadRequestException($this->request, 'Se requieren los campos indice, codigocrypto y user_id');
        }

        $result= $this->repository->create($data);

        return $this->respondWithData($result);
    }
}
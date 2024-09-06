<?php

declare(strict_types=1);

namespace App\Application\Actions\PrintedStickers;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpNotFoundException;

/**
 * @OA\Get(
 *     path="/printed-stickers/codigocrypto/{codigocrypto}",
 *     summary="View details of a printed sticker by codigocrypto",
 *     operationId="viewPrintedStickerByCodigocrypto",
 *     tags={"Printed Stickers"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(
 *         name="codigocrypto",
 *         in="path",
 *         description="Codigocrypto of printed sticker to view",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Printed sticker details",
 *         @OA\JsonContent(ref="#/components/schemas/PrintedStickers")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Printed sticker not found"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 */
class ViewByCodigocryptoAction extends PrintedStickersAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        // Obtener los parámetros de la consulta (query params) en lugar de usar resolveArg
        // $queryParams = $this->request->getQueryParams();
        // $codigocrypto = $queryParams['codigocrypto'] ?? '';
        $codigocrypto = $this->resolveArg('codigocrypto');
        $printedStickers = $this->repository->findByCodigocrypto($codigocrypto);
        if (!$printedStickers) {
            throw new HttpNotFoundException($this->request, 'Printed sticker not found');
        }

        return $this->respondWithData($printedStickers);
    }
}
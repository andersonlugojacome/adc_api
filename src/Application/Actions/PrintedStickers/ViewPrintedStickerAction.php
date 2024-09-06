<?php

declare(strict_types=1);

namespace App\Application\Actions\PrintedStickers;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpNotFoundException;

/**
 * @OA\Get(
 *     path="/printed-stickers/{id}",
 *     summary="View details of a printed sticker",
 *     operationId="viewPrintedSticker",
 *     tags={"Printed Stickers"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of printed sticker to view",
 *         required=true,
 *         @OA\Schema(type="integer")
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
class ViewPrintedStickerAction extends PrintedStickersAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $printedStickersId = (int) $this->resolveArg('id');
        $printedStickers = $this->repository->findById($printedStickersId);
        if (!$printedStickers) {
            throw new HttpNotFoundException($this->request, 'Printed sticker not found');
        }

        return $this->respondWithData($printedStickers);
    }
}
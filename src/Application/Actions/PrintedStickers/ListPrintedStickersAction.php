<?php

declare(strict_types=1);

namespace App\Application\Actions\PrintedStickers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Annotations as OA;
/**
 * @OA\Get(
 *     path="/printed-stickers",
 *     tags={"Printed Stickers"},
 *     summary="List all printed stickers",
 *     security={{"bearerAuth": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="List of printed stickers",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/PrintedStickers"))
 *     )
 * )
 */
class ListPrintedStickersAction extends PrintedStickersAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $printedStickers = $this->repository->findAll();
        $this->logger->info("Printed stickers list was viewed.");

        return $this->respondWithData($printedStickers);
    }
}
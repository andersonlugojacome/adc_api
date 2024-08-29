<?php

declare(strict_types=1);

namespace App\Application\Actions\CertificateConsecutives;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/certificate-consecutives/next",
 *     summary="Get the next consecutive number",
 *     tags={"certificate-consecutives"},
 *     @OA\Response(
 *         response=200,
 *         description="Next consecutive number",
 *         @OA\JsonContent(type="integer")
 *     )
 * )
 */
class NextConsecutiveAction extends CertificateConsecutivesAction
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
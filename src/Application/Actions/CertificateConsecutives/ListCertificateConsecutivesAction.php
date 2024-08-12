<?php

declare(strict_types=1);

namespace App\Application\Actions\CertificateConsecutives;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/certificate-consecutives",
 *    tags={"certificate-consecutives"},
 *     summary="List all certificate-consecutives",
 *     @OA\Response(
 *         response=200,
 *         description="List of certificate-consecutives",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/CertificateConsecutives"))
 *     )
 * )
 */
class ListCertificateConsecutivesAction extends CertificateConsecutivesAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $certificateConsecutives = $this->repository->findAll();

        $this->logger->info("CertificateConsecutives list was viewed.");

        return $this->respondWithData($certificateConsecutives);
    }
}


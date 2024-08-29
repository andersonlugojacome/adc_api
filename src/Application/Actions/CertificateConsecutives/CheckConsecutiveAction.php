<?php

declare(strict_types=1);

namespace App\Application\Actions\CertificateConsecutives;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     path="/certificate-consecutives/check",
 *     summary="Check if the consecutive and dateescritura exist for that year",
 *     tags={"certificate-consecutives"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="consecutivo", type="integer", example=123),
 *             @OA\Property(property="dateescritura", type="string", format="date", example="2023-08-15")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="The consecutive and dateescritura exist for that year",
 *         @OA\JsonContent(type="boolean")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input"
 *     )
 * )
 */
class CheckConsecutiveAction extends CertificateConsecutivesAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $data = $this->getFormData();
        $consecutivo = $data['consecutivo'];
        $dateescritura = $data['dateescritura'];
        $exists = $this->repository->checkConsecutive($consecutivo, $dateescritura);

        $this->logger->info("CertificateConsecutive of id `{$consecutivo}` was checked.");

        return $this->respondWithData($exists);
    }
}
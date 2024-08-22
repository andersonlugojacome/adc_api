<?php

declare(strict_types=1);

namespace App\Application\Actions\CertificateConsecutives;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     path="/certificate-consecutives",
 *     summary="Create a new Certificate Consecutive",
 *     tags={"certificate-consecutives"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="consecutivo", type="integer", example=123),
 *             @OA\Property(property="nroescriturapublica", type="integer", example=456),
 *             @OA\Property(property="dateescritura", type="string", format="date", example="2023-08-15"),
 *             @OA\Property(property="user_id", type="integer", example=789)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Certificate Consecutive created successfully",
 *         @OA\JsonContent(ref="#/components/schemas/CertificateConsecutives")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input"
 *     )
 * )
 */
class CreateCertificateConsecutivesAction extends CertificateConsecutivesAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $data = $this->getFormData();
        $certificateConsecutives = $this->repository->create($data);


        $this->logger->info("CertificateConsecutives of id `{$certificateConsecutives->getId()}` was created.");

        return $this->respondWithData($certificateConsecutives);
    }
}

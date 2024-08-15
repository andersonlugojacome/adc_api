<?php

declare(strict_types=1);

namespace App\Application\Actions\CertificateConsecutives;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Put(
 *     path="/certificate-consecutives/{id}",
 *     summary="Update an existing Certificate Consecutive",
 *     tags={"certificate-consecutives"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer"),
 *         description="ID of the Certificate Consecutive to update"
 *     ),
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
 *         description="Certificate Consecutive updated successfully",
 *         @OA\JsonContent(ref="#/components/schemas/CertificateConsecutives")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Certificate Consecutive not found"
 *     )
 * )
 */

class UpdateCertificateConsecutivesAction extends CertificateConsecutivesAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $certificateConsecutivesId = (int) $this->resolveArg('id');
        $data = $this->getFormData();
        $certificateConsecutives = $this->repository->update($certificateConsecutivesId, $data);

        $this->logger->info("CertificateConsecutives of id `{$certificateConsecutives->getId()}` was updated.");

        return $this->respondWithData($certificateConsecutives);
    }
}

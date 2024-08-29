<?php
declare(strict_types=1);

namespace App\Application\Actions\CertificateConsecutives;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/certificate-consecutives/check-nroescritura/{id}/{dateescritura}",
 *     summary="Check if the nroescriturapublica and dateescritura exist for that year",
 *    tags={"certificate-consecutives"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="nroescriturapublica", type="integer", example=123),
 *             @OA\Property(property="dateescritura", type="string", format="date", example="2023-08-15")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="The nroescriturapublica and dateescritura exist for that year",
 *         @OA\JsonContent(ref="#/components/schemas/CertificateConsecutives")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input"
 *     )
 * )
 */

class CheckNroescriturapublicaAction extends CertificateConsecutivesAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
       
        $nroescriturapublica =  $this->resolveArg('nroescriturapublica');
        $dateescritura = $this->resolveArg('dateescritura');
        $exists = $this->repository->checkNroescriturapublica((int)$nroescriturapublica, $dateescritura);

        $this->logger->info("CertificateConsecutive of id `{$nroescriturapublica}` was checked.");

        return $this->respondWithData($exists);
    }
}


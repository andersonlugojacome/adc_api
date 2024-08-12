<?php
declare(strict_types=1);

namespace App\Application\Actions\CertificateConsecutives;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/certificateConsecutives/{begingDate}/{endDate}",
 *     tags={"certificate-consecutives"},
 *     summary="List certificateConsecutives by date",
 *     description="Returns a list of certificateConsecutives by date",
 *     @OA\Parameter(
 *         name="begingDate",
 *         in="path",
 *         description="Beging date of certificateConsecutives to return",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="endDate",
 *         in="path",
 *         description="End date of certificateConsecutives to return",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="CertificateConsecutives response",
 *         @OA\JsonContent(ref="#/components/schemas/CertificateConsecutives")
 *     )
 * )
 */

class ListCertificateConsecutivesByDateAction extends CertificateConsecutivesAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $begingDate = $this->resolveArg('begingDate');
        $endDate = $this->resolveArg('endDate');
        $certificateConsecutives = $this->repository->findAllByDate($begingDate, $endDate);

        $this->logger->info("CertificateConsecutives list date Ranger was viewed.");

        return $this->respondWithData($certificateConsecutives);
    }
}
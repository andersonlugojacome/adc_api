<?php

declare(strict_types=1);

namespace App\Application\Actions\Permission;

use Psr\Http\Message\ResponseInterface as Response;
use OpenApi\Annotations as OA;
/**
 * @OA\Post(
 *     path="/permissions",
 *     summary="Create a new permission",
 *     operationId="createPermission",
 *     tags={"Permissions"},
 *     security={{"bearerAuth": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="description", type="string"),
 *             @OA\Property(property="status", type="string", enum={"active", "inactive"}, default="active")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Permission created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer"),
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="description", type="string"),
 *             @OA\Property(property="status", type="string"),
 *             @OA\Property(property="created_at", type="string", format="date-time"),
 *             @OA\Property(property="updated_at", type="string", format="date-time")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid request body"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 */
class CreatePermissionAction extends PermissionAction
{
    protected function action(): Response
    {
        $data = $this->request->getParsedBody();
        $result = $this->repository->create($data);

        $this->logger->info("Permission was created.");

        return $this->respondWithData($result);
    }
}

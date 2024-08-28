<?php 

declare(strict_types=1);

namespace App\Application\Actions\Permission;

use App\Domain\Permission\Permission;
use Psr\Http\Message\ResponseInterface as Response;


/**
 * @OA\Put(
 *     path="/permissions/{id}",
 *     tags={"Permissions"},
 *     summary="Update a permission",
 *     operationId="updatePermission",
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of permission to update",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="description", type="string"),
 *             @OA\Property(property="status", type="string", enum={"active", "inactive"}, default="active")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Permission updated successfully",
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

class UpdatePermissionAction extends PermissionAction
{
    protected function action(): Response
    {
        $id = (int) $this->resolveArg('id');
        $data = $this->request->getParsedBody();
        $result = $this->repository->update($id, $data);

        $this->logger->info("Permission of id `{$id}` was updated.");

        return $this->respondWithData($result);
    }
}
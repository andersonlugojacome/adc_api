<?php

declare(strict_types=1);

namespace App\Application\Actions\UserGroup;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use App\Infrastructure\Persistence\UserGroup\UserGroupRepository;
//Add anotation swagger
/**
 * @OA\Put(
 *     path="/user-group/{id}",
 *     tags={"user-group"},
 *     summary="Update a user group",
 *     description="Update a user group",
 *     @OA\Parameter(
 *         description="ID of user group to update",
 *         in="path",
 *         name="id",
 *         required=true,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         description="Update user group",
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/UserGroup")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="successful operation",
 *         @OA\JsonContent(ref="#/components/schemas/UserGroup")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User group not found"
 *     )
 * )
 */
class UpdateUserGroupAction extends UserGroupAction

{
     /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $userGroupId = (int) $this->resolveArg('id');
        $data = $this->getFormData();
        $userGroup = $this->repository->updateGroup($userGroupId, $data);

        $this->logger->info("User group of id `{$userGroupId}` was updated.");

        return $this->respondWithData($userGroup);
    }
    
}

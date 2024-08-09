<?php

declare(strict_types=1);

namespace App\Application\Actions\UserGroup;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
//Add anotation swagger
/**
 * @OA\Get(
 *     path="/user-group/{id}",
 *     tags={"user-group"},
 *     summary="Return a single user group",
 *     description="Return a single user group",
 *     @OA\Parameter(
 *         description="ID of user group to return",
 *         in="path",
 *         name="id",
 *         required=true,
 *         @OA\Schema(
 *             type="integer"
 *         )
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
class ViewUserGroupAction extends UserGroupAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $userGroupId = (int) $this->resolveArg('id');
        $userGroup = $this->userGroupRepository->findGroupOfId($userGroupId);

        $this->logger->info("User group of id `{$userGroupId}` was viewed.");

        return $this->respondWithData($userGroup);
    }
}

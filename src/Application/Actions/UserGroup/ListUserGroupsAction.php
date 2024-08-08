<?php

declare(strict_types=1);

namespace App\Application\Actions\UserGroup;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

//Add anotation swagger
/**
 * @OA\Get(
 *     path="/user-group",
 *     tags={"user-group"},
 *     summary="Return all user groups",
 *     description="Return all user groups",
 *     @OA\Response(
 *        response=200,
 *       description="successful operation",
 *      @OA\JsonContent(ref="#/components/schemas/UserGroup")
 *    )
 * )
 */

class ListUserGroupsAction extends UserGroupAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $userGroups = $this->repository->findAll();

        $this->logger->info("User groups list was viewed.");

        return $this->respondWithData($userGroups);
    }
}

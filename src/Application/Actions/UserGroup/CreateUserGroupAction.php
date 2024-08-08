<?php

declare(strict_types=1);

namespace App\Application\Actions\UserGroup;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use App\Infrastructure\Persistence\UserGroup\UserGroupRepository;

//Add anotation swagger
/**
 * @OA\Post(
 *     path="/user-group",
 *     tags={"user-group"},
 *     summary="Create a new user group",
 *     description="Create a new user group",
 *     @OA\RequestBody(
 *         description="Create user group",
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/UserGroup")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(ref="#/components/schemas/UserGroup")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid data",
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error",
 *     )
 * )
 */

class CreateUserGroupAction extends UserGroupAction
{
     /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $data = $this->getFormData();
        $userGroup = $this->repository->createGroup($data);

        $this->logger->info("User group of id `{$userGroup}` was created.");

        return $this->respondWithData($userGroup);
    }

}

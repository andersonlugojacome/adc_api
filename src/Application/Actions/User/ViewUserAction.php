<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;
use OpenApi\Annotations as OA;
/**
 * @OA\Get(
 *     path="/users/{id}",
 *    tags={"users"},
 *     summary="Get user by id",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of user to return",
 *         required=true,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User response",
 *         @OA\JsonContent(ref="#/components/schemas/User")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found"
 *     )
 * )
 */
class ViewUserAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $userId = (int) $this->resolveArg('id');
        $user = $this->userRepository->findUserOfId($userId);
        //Hidde password
        
       

        $this->logger->info("User of id `{$userId}` was viewed.");

        return $this->respondWithData($user);
    }
}

<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Annotations as OA;
/**
 * @OA\Get(
 *     path="/users",
 *     summary="List all users",
 *     @OA\Response(
 *         response=200,
 *         description="List of users",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/User"))
 *     )
 * )
 */
class ListUsersAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $users = $this->userRepository->findAll();
        //Hidde password
        foreach ($users as $key => $value) {
            unset($users[$key]['password']);
        }
        

        $this->logger->info("Users list was viewed.");

        return $this->respondWithData($users);
    }
}

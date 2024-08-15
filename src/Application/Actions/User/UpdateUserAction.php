<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;


class UpdateUserAction extends UserAction
{
    protected function action(): Response
    {
        $userId = (int) $this->resolveArg('id');
        $data = $this->request->getParsedBody();
        $user = $this->userRepository->updateUser($userId, $data);

        $this->logger->info("User of id `{$userId}` was updated.");

        return $this->respondWithData($user);
    }
}
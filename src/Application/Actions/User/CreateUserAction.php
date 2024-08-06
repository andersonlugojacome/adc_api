<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

class CreateUserAction extends UserAction
{
    protected function action(): Response
    {
        $data = $this->request->getParsedBody();
        $user = $this->userRepository->createUser($data);

        $this->logger->info("User was created.");

        return $this->respondWithData($user);
    }
}

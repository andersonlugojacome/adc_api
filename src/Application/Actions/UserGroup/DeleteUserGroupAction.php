<?php

declare(strict_types=1);

namespace App\Application\Actions\UserGroup;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use App\Infrastructure\Persistence\UserGroup\UserGroupRepository;

class DeleteUserGroupAction extends UserGroupAction
{

     /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $userGroupId = (int) $this->resolveArg('id');
        $userGroup = $this->userGroupRepository->findGroupOfId($userGroupId);

        if ($userGroup) {
            $this->userGroupRepository->deleteGroup($userGroupId);
            $this->logger->info("User group of id `{$userGroupId}` was deleted.");
            return $this->respondWithData("User group of id `{$userGroupId}` was deleted.");
        }

        return $this->respondWithData("User group of id `{$userGroupId}` not found.", 404);
    }
    
}

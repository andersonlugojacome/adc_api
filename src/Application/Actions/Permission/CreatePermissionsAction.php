<?php

declare(strict_types=1);

namespace App\Application\Actions\Permission;

use App\Domain\Permission\Permission;
use Psr\Http\Message\ResponseInterface as Response;
use OpenApi\Annotations as OA;

class CreatePermissionsAction extends PermissionAction
{
    protected function action(): Response
    {
        $data = $this->request->getParsedBody();
        $result = $this->repository->create($data);

        $this->logger->info("Permission was created.");

        return $this->respondWithData($result);
    }
}

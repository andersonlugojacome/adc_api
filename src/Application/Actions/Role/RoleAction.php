<?php

declare(strict_types=1);

namespace App\Application\Actions\Role;

use App\Application\Actions\Action;
use App\Domain\Role\RoleRepository;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


abstract class RoleAction extends Action
{
    protected RoleRepository $roleRepository;

    public function __construct(LoggerInterface $logger, RoleRepository $roleRepository)
    {
        parent::__construct($logger);
        $this->roleRepository = $roleRepository;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;

        return $this->action();
    }

    abstract protected function action(): Response;
}

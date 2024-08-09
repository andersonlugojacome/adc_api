<?php

declare(strict_types=1);

namespace App\Application\Actions\UserGroup;

use App\Application\Actions\Action;
use App\Domain\UserGroup\UserGroupRepository;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


abstract class UserGroupAction extends Action
{
    protected UserGroupRepository $userGroupRepository;

    public function __construct(LoggerInterface $logger, UserGroupRepository $userGroupRepository)
    {
        parent::__construct($logger);
        $this->userGroupRepository = $userGroupRepository;
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

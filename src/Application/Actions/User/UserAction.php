<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Application\Actions\Action;
use App\Domain\User\UserRepository;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


abstract class UserAction extends Action
{
    protected UserRepository $userRepository;

    public function __construct(LoggerInterface $logger, UserRepository $userRepository)
    {
        parent::__construct($logger);
        $this->userRepository = $userRepository;
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

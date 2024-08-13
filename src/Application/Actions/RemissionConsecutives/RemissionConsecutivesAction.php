<?php

declare(strict_types=1);

namespace App\Application\Actions\RemissionConsecutives;

use App\Application\Actions\Action;
use App\Domain\RemissionConsecutives\RemissionConsecutivesRepository;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


abstract class RemissionConsecutivesAction extends Action
{
    protected RemissionConsecutivesRepository $repository;

    public function __construct(LoggerInterface $logger, RemissionConsecutivesRepository $repository)
    {
        parent::__construct($logger);
        $this->repository = $repository;
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

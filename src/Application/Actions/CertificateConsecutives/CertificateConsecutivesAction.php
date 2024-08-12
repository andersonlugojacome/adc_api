<?php

declare(strict_types=1);

namespace App\Application\Actions\CertificateConsecutives;

use App\Application\Actions\Action;
use App\Domain\CertificateConsecutives\CertificateConsecutivesRepository;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


abstract class CertificateConsecutivesAction extends Action
{
    protected CertificateConsecutivesRepository $repository;

    public function __construct(LoggerInterface $logger, CertificateConsecutivesRepository $repository)
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

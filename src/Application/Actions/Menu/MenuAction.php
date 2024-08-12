<?php

declare(strict_types=1);

namespace App\Application\Actions\Menu;

use App\Application\Actions\Action;
use App\Domain\Menu\MenuRepository;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


abstract class MenuAction extends Action
{
    protected MenuRepository $menuRepository;

    public function __construct(LoggerInterface $logger, MenuRepository $menuRepository)
    {
        parent::__construct($logger);
        $this->menuRepository = $menuRepository;
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

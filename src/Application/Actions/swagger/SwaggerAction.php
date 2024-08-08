<?php

declare(strict_types=1);

namespace App\Application\Actions\Swagger;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response as SlimResponse;
use Psr\Container\ContainerInterface;

class SwaggerAction
{
    private $openapi;

    public function __construct(ContainerInterface $container)
    {
        $this->openapi = $container->get('openapi');
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $response = new SlimResponse();
        $response->getBody()->write($this->openapi->toJson());
        return $response->withHeader('Content-Type', 'application/json');
    }
}

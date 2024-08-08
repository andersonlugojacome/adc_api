<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use App\Application\Actions\User\CreateUserAction;
use App\Application\Actions\User\UpdateUserAction;
use App\Application\Actions\User\LoginAction;
use App\Application\Actions\Swagger\SwaggerAction;
use App\Application\Middleware\JwtMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;


return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });
   

    $app->get('/', function (Request $request, Response $response) {
        
        $response->getBody()->write('Hello world!');
        return $response;
    });

    // Ruta para la documentación Swagger
    $app->get('/docs/swagger.json', SwaggerAction::class);

    // Ruta para la interfaz Swagger UI
    $app->get('/docs', function (Request $request, Response $response) {
        $response->getBody()->write(file_get_contents(__DIR__ . '/../public/swagger/index.html'));
        return $response->withHeader('Content-Type', 'text/html');
    });
    
     // Ruta de autenticación
     $app->post('/login', LoginAction::class);

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
        $group->post('', CreateUserAction::class);
        $group->put('/{id}', UpdateUserAction::class);
    })->add(JwtMiddleware::class);

    
};

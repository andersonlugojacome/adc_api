<?php

declare(strict_types=1);

namespace App\Application\Middleware;

use App\Application\Services\JWTService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpUnauthorizedException;
// Add anotation for swagger
use OpenApi\Annotations as OA;

class JwtMiddleware
{
    private JWTService $jwtService;

    public function __construct(JWTService $jwtService)
    {
        $this->jwtService = $jwtService;
    }
    

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $path = $request->getUri()->getPath();

        // Permitir solicitudes preflight sin autenticación
    if ($request->getMethod() === 'OPTIONS') {
        $response = new \Slim\Psr7\Response();
        return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
            ->withHeader('Access-Control-Allow-Credentials', 'true')
            ->withStatus(204);
    }



        // Permitir el acceso sin token a las rutas de inicio de sesión y registro
        if ($path === '/login' || $path === '/register' || $path === '/' || $path === '/docs' || $path === '/docs/swagger.json') {
            return $handler->handle($request);
        }

        $authHeader = $request->getHeader('Authorization');
        if (!$authHeader || count($authHeader) === 0) {
            throw new HttpUnauthorizedException($request, 'Token not provided.');
        }

        $token = str_replace('Bearer ', '', $authHeader[0]);
        error_log('Token received: ' . $token);

        try {
            $this->jwtService->validateToken($token);
        } catch (\Exception $e) {
            throw new HttpUnauthorizedException($request, 'Invalid token.');
        }

        return $handler->handle($request);
    }
}

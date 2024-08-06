<?php

declare(strict_types=1);

namespace App\Application\Middleware;

use App\Application\Services\JWTService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpUnauthorizedException;

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


        // Permitir el acceso sin token a las rutas de inicio de sesión y registro
        if ($path === '/login' || $path === '/register' || $path === '/') {
            return $handler->handle($request);
        }



        $authHeader = $request->getHeader('Authorization');
        if (!$authHeader) {
            throw new HttpUnauthorizedException($request, 'Token not provided.');
        }

        $token = str_replace('Bearer ', '', $authHeader[0]);
        try {
            $this->jwtService->validateToken($token);
        } catch (\Exception $e) {
            throw new HttpUnauthorizedException($request, 'Invalid token.');
        }

        return $handler->handle($request);
    }
}

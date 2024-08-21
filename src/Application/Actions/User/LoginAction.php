<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Application\Services\JWTService;
use App\Domain\User\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *    path="/login",
 * tags={"users"},
 *   summary="Login",
 *  description="Login by username and password",
 *  @OA\RequestBody(
 *      @OA\JsonContent(
 *          type="object",
 *          @OA\Property(property="username", type="string"),
 *          @OA\Property(property="password", type="string")
 *      )
 *  ),
 *  @OA\Response(
 *      response=200,
 *      description="Login",
 *      @OA\JsonContent(
 *          type="object",
 *          @OA\Property(property="token", type="string")
 *      )
 *  )
 * )
 */

class LoginAction extends UserAction
{
    private JWTService $jwtService;

    public function __construct(LoggerInterface $logger, UserRepository $userRepository, JWTService $jwtService)
    {
        parent::__construct($logger, $userRepository);
        $this->jwtService = $jwtService;
    }


    protected function action(): Response
    {
        $data = $this->getFormData();
        $ipAddress = $this->request->getServerParams()['REMOTE_ADDR'];

        // Verificar si la cuenta está bloqueada
        if ($this->userRepository->isAccountLocked($data['username'], $ipAddress)) {
            $response = $this->response->withStatus(403);
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Account Locked',
                    'message' => 'Too many failed login attempts. Please try again later.',
                ]
            ));
            return $response;
        }

        $user = $this->userRepository->findUserByUsername($data['username']);

        // Verificar si el usuario está activo
        if (!$user || !$user->getIsActive()) {
            $this->userRepository->registerFailedAttempt($data['username'], $ipAddress);
            $response = $this->response->withStatus(401);
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Unauthorized',
                    'message' => 'User is not active or does not exist',
                ]
            ));
            return $response;
        }

        // Verificar contraseña
        if (!$user || $user->getPassword() !== sha1(md5($data['password']))) {
            $this->userRepository->registerFailedAttempt($data['username'], $ipAddress);
            $response = $this->response->withStatus(401);
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Unauthorized',
                    'message' => 'Invalid username or password',
                ]
            ));
            return $response;
        }

        // Si el login es exitoso, reiniciar los intentos fallidos
        $this->userRepository->resetFailedAttempts($data['username'], $ipAddress);

        $token = $this->jwtService->generateToken([
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'name' => $user->getName(),
            'lastname' => $user->getLastName(),
            'gender' => $user->getGender(),
            'email' => $user->getEmail(),
            'isActive' => $user->getIsActive(),
        ]);

        return $this->respondWithData(['token' => $token]);
    }







    // protected function action(): Response
    // {
    //     $data = $this->getFormData();

    //     $user = $this->userRepository->findUserByUsername($data['username']);

    //     // verify if user is active
    //     if (!$user->getIsActive()) {
    //         $response = $this->response->withStatus(401);
    //         $response->getBody()->write(json_encode(
    //             [
    //                 'error' => 'Unauthorized',
    //                 'message' => 'User is not active or does not exist',
    //             ]
    //         ));
    //         return $response;
    //     }

    //     if (!$user || $user->getPassword() !== sha1(md5($data['password']))) {
    //         $response = $this->response->withStatus(401);
    //         $response->getBody()->write(json_encode(
    //             [
    //                 'error' => 'Unauthorized',
    //                 'message' => 'Invalid username or password',
    //             ]
    //         ));
    //         return $response;
    //     }
    //     $token = $this->jwtService->generateToken([
    //         'id' => $user->getId(),
    //         'username' => $user->getUsername(),
    //     ]);

    //     return $this->respondWithData(['token' => $token]);
    // }
}

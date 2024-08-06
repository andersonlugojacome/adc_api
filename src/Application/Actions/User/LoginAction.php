<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Application\Services\JWTService;
use App\Infrastructure\Persistence\User\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;


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
        
        $user = $this->userRepository->findUserByUsername($data['username']);
        
        //pirnt json password send by user

        //return $this->respondWithData(sha1(md5($data['password'])));

        if (!$user || $user->getPassword() !== sha1(md5($data['password']))) {
            $response = $this->response->withStatus(401);
            // print $user in getBody

            $response->getBody()->write(json_encode($user, JSON_PRETTY_PRINT));
            return $response;
        }

        $token = $this->jwtService->generateToken([
            'id' => $user->getId(),
            'username' => $user->getUsername(),
        ]);

        return $this->respondWithData(['token' => $token]);
    }

}

<?php

declare(strict_types=1);

namespace App\Application\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTService
{
    private string $secret;

    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    public function generateToken(array $data): string
    {
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600; // jwt valid for 1 hour from the issued time
        $payload = array_merge($data, [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            

        ]);

        return JWT::encode($payload, $this->secret, 'HS256');
    }

    public function validateToken(string $token): object
    {
        return JWT::decode($token, new Key($this->secret, 'HS256'));
    }
}

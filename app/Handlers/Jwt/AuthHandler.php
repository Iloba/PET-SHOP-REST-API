<?php

namespace App\Handlers\Jwt;

use Firebase\JWT\JWT;
use DateTimeImmutable;

class AuthHandler
{
    /**
     * Handles operations related to admin authentication
     */

    // generate token
    public function GenerateToken($user): string
    {
        $secretKey  = env('JWT_KEY');
        $tokenId    = base64_encode(random_bytes(16));
        $issuedAt   = new DateTimeImmutable();
        $expire     = $issuedAt->modify('+60 minutes')->getTimestamp();
        $serverName = env("APP_URL");
        $userID   = $user->uuid;

        // Create the token as an array
        $data = [
            'iat'  => $issuedAt->getTimestamp(),
            'jti'  => $tokenId,
            'iss'  => $serverName,
            'nbf'  => $issuedAt->getTimestamp(),
            'exp'  => $expire,
            'data' => [
                'user_uuid' => $userID,
            ]
        ];

    // Encode the array to a JWT string.
        $token = JWT::encode(
            $data,
            $secretKey,
            'HS512'
        );
        return $token;
    }
}

<?php

namespace App\Helpers;

use Exception;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use DateTimeImmutable;
use Illuminate\Auth\AuthenticationException;

class PublicHelper
{
    // get jwt info from header
    public function GetRawJWT(): string
    {
        // check if header exists
        if(empty($_SERVER['HTTP_AUTHORIZATION'])) {
            throw new AuthenticationException('authorization header not found');
        }

        // check if bearer token exists
        if (! preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
            throw new AuthenticationException('token not found');
        }

        // extract token
        $jwt = $matches[1];
        if (!$jwt) {
            throw new AuthenticationException('could not extract token');
        }

        return $jwt;
    }

    public function DecodeRawJWT($jwt): string
    {
        // use secret key to decode token
        $secretKey  = env('JWT_KEY');
        try {
            $token = JWT::decode($jwt, new Key($secretKey, 'HS512'));
            $now = new DateTimeImmutable();
        } catch(Exception $e) {
            throw new AuthenticationException('unauthorized');
        }

        return $token;
    }

    public function GetAndDecodeJWT(): string
    {
        $jwt = $this->GetRawJWT();
        $token = $this->DecodeRawJWT($jwt);

        return $token;
    }

    public function getAuthenticatedUser($token)
    {

        $user = User::where('uuid', $token->data->user_uuid)->first();

        if (!$user) {
            abort(403, "User not found");
        }
        return $user;
    }
}

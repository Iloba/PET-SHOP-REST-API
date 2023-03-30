<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AccessToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Handlers\Jwt\AuthHandler;

class APIController extends Controller
{
    // methods to handle API responses

    public function sendResponse($data, $message, $status = 200): JsonResponse
    {
        $response = [
            'data' => $data,
            'message' => $message
        ];

        return response()->json($response, $status);
    }

    public function sendError($message, $errorData = [], $status = 400): JsonResponse
    {
        $response = [
            'message' => $message
        ];

        if(!empty($errorData)) {
            $response['data'] = $errorData;
        }

        return response()->json($response, $status);
    }

    public function resourceNotFoundResponse(string $resource): JsonResponse
    {
        $response = [
            'error' => "The $resource wasn't found",
        ];

        return response()->json($response, 404);
    }

    protected function respondWithToken($user) : string
    {
        $authHandler = new AuthHandler();
        return $authHandler->GenerateToken($user);

    }

    public function saveToken($token)
    {
        $AccessToken = new AccessToken();
        $AccessToken->token = $token;
        $AccessToken->is_valid = true;
        $AccessToken->save();
    }

    public function getAuthenticatedUser($token)
    {
        $user = User::where('uuid', $token->data->user_uuid)->first();
        if (!$user) {
            abort(403, "User not found");
        }
        return $user;
    }

    public function invalidateToken($token)
    {
        $userToken = AccessToken::where('token', $token)->first();
        if(!$userToken){
            abort(403, "user Token Not found");
        }
        $userToken->is_valid = false;
        $userToken->save();

    }

    public function checkTokenValidity($token)
    {
        $tokenFromDB = AccessToken::where('token', $token)->first();

        if (!$tokenFromDB->is_valid) {
            abort(401, 'Token no longer Valid');
        }
    }
}

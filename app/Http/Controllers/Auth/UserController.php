<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\PublicHelper;
use App\Http\Middleware\VerifyJwt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\APIController;
use App\Services\Auth\CreateUserService;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\User\CreateUserRequest;
use App\Models\AccessToken;

class UserController extends APIController
{
    public function store(CreateUserRequest $request, CreateUserService $createUserService)
    {
        $user = $createUserService->create($request->validated());

        if ($user) {
            $token =  $this->respondWithToken($user);
            $success = [
                'user' => $user,
                'token' => $token,
            ];

            $this->saveToken($token);

            return $this->sendResponse($success, 'user registered successfully', 201);
        } else {
            return $this->sendError('error', 'Something Went Wrong');
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8'
        ], [
            'email.exists' => "This email does not match any of our records"
        ]);

        if ($validator->fails()) {
            return $this->sendError('error', $validator->errors());
        }

        if (Auth::attempt($validator->validated())) {
            $user = Auth::user();
            $token =  $this->respondWithToken($user);
            $success = [
                'token' => $token,
            ];

            $this->saveToken($token);

            return $this->sendResponse($success, 'Login Successful', 200);
        } else {
            return $this->sendError('error', 'Invalid Credentials');
        }
    }

    public function editUser(CreateUserRequest $request, PublicHelper $publicHelper)
    {
        $token = $publicHelper->GetAndDecodeJWT();
        $rawtoken = $publicHelper->GetRawJWT();
        $this->checkTokenValidity($rawtoken);
        $user = $this->getAuthenticatedUser($token);

        $password = Hash::make($request->validated()['password']);
        $ValidatedData = array_merge($request->validated(), ['password' => $password]);
        $user->update($ValidatedData);
        $success = [
            'user' => $user,
        ];
        return $this->sendResponse($success, 'Update Successful', 200);
    }

    public function profile(PublicHelper $publicHelper)
    {
        $token = $publicHelper->GetAndDecodeJWT();
        $rawtoken = $publicHelper->GetRawJWT();
        $this->checkTokenValidity($rawtoken);
        $user = $this->getAuthenticatedUser($token);
        $success = [
            'user_profile' => $user,
        ];

        return $this->sendResponse($success, 'User Profile', 200);
    }

    public function delete(PublicHelper $publicHelper)
    {
        $token = $publicHelper->GetAndDecodeJWT();
        $rawtoken =  $publicHelper->GetRawJWT();
        $this->checkTokenValidity($rawtoken); 
        $user = $this->getAuthenticatedUser($token);
        $user->delete();
        $this->invalidateToken($token);
        return $this->sendResponse([], 'User Account Deleted ', 200);
    }

    public function logout(PublicHelper $publicHelper)
    {
        Auth::logout();
        $token = $publicHelper->GetRawJWT();
        $this->invalidateToken($token);
        return $this->sendResponse([], 'Logout Successful', 200);
    }

    public function saveToken($token)
    {
        $AccessToken = new AccessToken;
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

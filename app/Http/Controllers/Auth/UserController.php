<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\APIController;
use App\Services\Auth\CreateUserService;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\User\CreateUserRequest;

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

            return $this->sendResponse($success, 'user registered successfully', 201);
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

            return $this->sendResponse($success, 'Login Successful', 200);
        } else {
            return $this->sendError('error', 'Invalid Credentials');
        }
    }

    public function logout()
    {
        Auth::logout();

        return $this->sendResponse([], 'Logout Successful', 200);
    }

   
}

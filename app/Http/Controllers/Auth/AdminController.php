<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\APIController;
use App\Http\Requests\User\CreateUserRequest;
use App\Services\Auth\CreateAdminService;

class AdminController extends APIController
{
    public function store(CreateUserRequest $request, CreateAdminService $createAdminService)
    {
        $user = $createAdminService->create($request->validated());

        if ($user) {

            $token =  $this->respondWithToken($user);
            $success = [
                'user' => $user,
                'token' => $token,
            ];

            return $this->sendResponse($success, 'Admin registered successfully', 201);
        }
    }

    public function login(Request $request)
    {
      
    }

    public function logout()
    {
       
    }
}

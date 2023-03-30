<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\PublicHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\APIController;
use App\Services\Auth\CreateAdminService;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\User\CreateUserRequest;

class AdminController extends APIController
{
    public function store(CreateUserRequest $request, CreateAdminService $createAdminService): JsonResponse
    {
        $user = $createAdminService->create($request->validated());

        if ($user) {

            $token =  $this->respondWithToken($user);
            $success = [
                'user' => $user,
                'token' => $token,
            ];
            $this->saveToken($token);

            return $this->sendResponse($success, 'Admin registered successfully', 201);
        }
    }

    public function login(Request $request): JsonResponse
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

    public function users(PublicHelper $publicHelper): JsonResponse
    {
        $token = $publicHelper->GetRawJWT();
        $this->checkTokenValidity($token);
        $users = User::allNonAdminUsers()->get();

        $success = [
            'users' => $users,
        ];

        return $this->sendResponse($success, 'All users', 200);
    }

    public function editUser($uuid, CreateUserRequest $request): JsonResponse
    {
        $user = $this->findUserByUuid($uuid);
        $password = Hash::make($request->validated()['password']);
        $ValidatedData = array_merge($request->validated(), ['password' => $password]);
        $user->update($ValidatedData);
        $success = [
            'user' => $user,
        ];

        return $this->sendResponse($success, 'User Update Successful', 200);
    }

    public function deleteUser($uuid): JsonResponse
    {
        $user = $this->findUserByUuid($uuid);
        $user->delete();
        return $this->sendResponse([], 'User Account Deleted ', 200);
    }

    public function logout(PublicHelper $publicHelper): JsonResponse
    {


        $token = $publicHelper->GetRawJWT();
        $this->checkTokenValidity($token);
        Auth::logout();
        $this->invalidateToken($token);
        return $this->sendResponse([], 'Logout Successful', 200);
    }

    public function findUserByUuid($uuid)
    {
        $user = User::where('uuid', $uuid)->first();
        if (!$user) {
            abort(403, "User not Found");
        }
        return $user;
    }
}

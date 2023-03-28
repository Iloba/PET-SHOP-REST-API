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

    public function editUser(CreateUserRequest $request)
    {
        $publicHelper = new PublicHelper();
        $token = $publicHelper->GetAndDecodeJWT();
        $user = User::where('uuid', $token->data->user_uuid)->first();
        if(!$user){
            return $this->sendError('error', "No such user found");
        }
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
        $user =  User::where('uuid', $token->data->user_uuid)->first();
        if(!$user){
            return $this->sendError('error', "No such user found");
        }
       
        $success = [
            'user' => $user,
        ];
        return $this->sendResponse($success, 'User Profile', 200);
    }

    public function delete(PublicHelper $publicHelper)
    {
        $token = $publicHelper->GetAndDecodeJWT();
        $user =  User::where('uuid', $token->data->user_uuid)->first();
        if(!$user){
            return $this->sendError('error', "No such user found");
        }
        $user->delete();

        return $this->sendResponse([], 'User Account Deleted ', 200);
    }

    public function logout()
    {
        Auth::logout();
        //On Logout Delete Token
        return $this->sendResponse([], 'Logout Successful', 200);
    }
}

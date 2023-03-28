<?php

namespace App\Services\Auth;

use App\Helpers\PublicHelper;
use Exception;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class CreateUserService
{
    public function create(array $data)
    {

        $password = Hash::make($data['password']);
        $uuid = (string)Str::uuid();
        $data2 = array_merge(['uuid' => $uuid],   $data, ['password' => $password]);
        $user = User::create($data2);

      

        return $user;
    }
}

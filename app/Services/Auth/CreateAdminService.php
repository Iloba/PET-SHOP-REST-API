<?php

namespace App\Services\Auth;


use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class CreateAdminService
{
    public function create(array $data)
    {
        $password = Hash::make($data['password']);
        $uuid = (string)Str::uuid();
        $data2 = array_merge(['is_admin' => true], ['uuid' => $uuid],  $data, ['password' => $password], );
        $user = User::create($data2);

        return $user;
    }
}

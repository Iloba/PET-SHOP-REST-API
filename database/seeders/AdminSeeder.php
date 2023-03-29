<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'uuid' => (string) Str::uuid(),
            'first_name' => 'Admin',
            'last_name' => 'User',
            'is_admin' => true,
            'email' => 'adminuser@gmail.com',
            'password' => Hash::make('password'),
            'address' => "No 7 Admin Close Malali Kaduna",
            'phone_number' => "091098771985",
        ]);
    }
}

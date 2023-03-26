<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use DatabaseMigrations;
    public function test_that_a_user_can_be_created_successfully(): void
    {
        //Arrange
        $data = [
            'first_name' => "Timothy",
            'last_name' => "Iloba",
            'email' => "Timothy@example.com",
            'password' => "Timothy@123",
            'password_confirmation' => "Timothy@123",
            'address' => "Malali Kaduna, Nigeria",
            'phone_number' => "09087761233"
        ];
        $this->withExceptionHandling();
        $createUser = $this->postJson(route('create.user', $data));
        $createUser->assertStatus(201);
        $this->assertTrue($createUser['success']);
        $this->assertTrue($createUser['user']);
        $this->assertTrue($createUser['token']);

        //Act check db

        //Assert
    }
}

<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Handlers\Jwt\AuthHandler;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    public function test_that_a_user_can_be_created_successfully(): void
    {

        $data = [
            'first_name' => "Timothy",
            'last_name' => "Iloba",
            'email' => "Timothy@example.com",
            'password' => "Timothy@123",
            'password_confirmation' => "Timothy@123",
            'address' => "Malali Kaduna, Nigeria",
            'phone_number' => "09087761233"
        ];

        $createUser = $this->postJson(route('create.user', $data));
        $createUser->assertValid();
        $createUser->assertStatus(201);
        $createUser->assertJson(
            fn (AssertableJson $json) =>
            $json->hasAll(['data', 'message'])->missing('error')
        );

        $user = User::orderBy('id', 'desc')->first();
        $this->assertEquals($user->first_name, "Timothy");
        $this->assertEquals($user->last_name, "Iloba");
        $this->assertEquals($user->email, "Timothy@example.com");
        $this->assertEquals($user->address, "Malali Kaduna, Nigeria");
        $this->assertEquals($user->phone_number, "09087761233");
    }

    public function test_that_only_a_user_with_right_credentials_can_login(): void
    {
        $loginUser = $this->postJson(route('login.user', [
            'email' => $this->user->email,
            'password' => 'password'
        ]));

        $loginUser->assertValid();
        $loginUser->assertStatus(200);
        $loginUser->assertJson(
            fn (AssertableJson $json) =>
            $json->hasAll(['data', 'message'])->missing('error')
        );
    }

    public function test_that_users_with_wrong_credentials_cannot_login() : void
    {
        $loginUser = $this->postJson(route('login.user', [
            'email' => "some@gmail.com",
            'password' => 'somepassword'
        ]));
        $loginUser->assertStatus(400);
        $loginUser->assertJson(
            fn (AssertableJson $json) =>
            $json->hasAll(['data', 'message'])->missing('token')
        );
    }

    public function test_that_a_user_can_edit_profile(): void
    {
       
    }
    public function test_that_a_user_can_logout() :void
    {
    //     $logoutUser = $this->actingAs($this->user)->withHeaders([
    //         'Accept' => 'application/json',
    //         'Authorization' => 'Bearer ' . '$response->getContent()'
    //     ])->postJson(route('logout.user'));
      
    //    dd( $logoutUser->getContent());
    //     $logoutUser->assertStatus(200);
          
    //     $logoutUser->assertJson(
    //         fn (AssertableJson $json) =>
    //         $json->hasAll(['data', 'message'])->missing('token')
    //     );
    }
}

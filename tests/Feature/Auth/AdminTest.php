<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdminTest extends TestCase
{
    use DatabaseMigrations;

    public function test_that_an_admin_can_be_created_successfully(): void
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

        $createAdmin = $this->postJson(route('create.admin', $data));
        $createAdmin->assertValid();
        $createAdmin->assertStatus(201);
        $createAdmin->assertJson(
            fn (AssertableJson $json) =>
            $json->hasAll(['data', 'message'])->missing('error')
        );

        $admin = User::orderBy('id', 'desc')->first();
        $this->assertEquals($admin->first_name, "Timothy");
        $this->assertEquals($admin->last_name, "Iloba");
        $this->assertEquals($admin->email, "Timothy@example.com");
        $this->assertEquals($admin->address, "Malali Kaduna, Nigeria");
        $this->assertEquals($admin->phone_number, "09087761233");
    }

    public function test_that_only_an_admin_with_right_credentials_can_login(): void
    {
        $loginAdmin = $this->postJson(route('login.admin', [
            'email' => $this->user->email,
            'password' => 'password'
        ]));

        $loginAdmin->assertValid();
        $loginAdmin->assertStatus(200);
        $loginAdmin->assertJson(
            fn (AssertableJson $json) =>
            $json->hasAll(['data', 'message'])->missing('error')
        );
    }

    public function test_that_admin_with_wrong_credentials_cannot_login() : void
    {
        $loginAdmin = $this->postJson(route('login.admin', [
            'email' => "lasttime@gmail.com",
            'password' => 'password'
        ]));
        $loginAdmin->assertStatus(400);
        $loginAdmin->assertJson(
            fn (AssertableJson $json) =>
            $json->hasAll(['data', 'message'])->missing('token')
        );
    }

    public function test_that_an_admin_can_logout() :void
    {
    //     $logoutUser = $this->actingAs($this->user)->withHeaders([
    //         'HTTP_Accept' => 'application/json',
    //         'HTTP_Authorization' => 'Bearer ' . '$response->getContent()'
    //     ])->postJson(route('logout.user'));
      
    //    dd( $logoutUser->getContent());
        // $logoutUser->assertStatus(200);
          
        // $logoutUser->assertJson(
        //     fn (AssertableJson $json) =>
        //     $json->hasAll(['data', 'message'])->missing('token')
        // );
    }
}

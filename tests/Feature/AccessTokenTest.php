<?php

namespace Tests\Feature;

use App\Models\AccessToken;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AccessTokenTest extends TestCase
{
    use DatabaseMigrations;

    public function test_that_all_tokens_created_are_stored(): void
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
        $response = $this->postJson(route('create.user'), $data);

        $response->assertStatus(201);
        $token = AccessToken::factory()->create();

        $this->assertDatabaseHas('access_tokens', [
            'token' => $token->token,
            'is_valid' => $token->is_valid
        ]);

        $latestTokenStored = AccessToken::orderBy('id', 'desc')->first();
        $this->assertEquals($latestTokenStored->token, $token->token);
        $this->assertEquals($latestTokenStored->is_valid, $token->is_valid);
    }
}
